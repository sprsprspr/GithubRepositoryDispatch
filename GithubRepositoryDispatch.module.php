<?php namespace ProcessWire;

class GithubRepositoryDispatch extends WireData implements Module {

  // public function __construct() {
  //   echo "construct";
  //   set_error_handler(array($this, 'handleErrorCallback'));
  //   set_exception_handler(array($this, 'handleErrorCallback'));
  // }

  private function getTimeInterval() {
    $timeInterval = 'everyDay';

    $data = $this->modules->getConfig($this->className);
    if (isset($data['timeInterval'])) {
      $timeInterval = $data['timeInterval'];
    }

    return $timeInterval;
  }

  public function init() {
    $this->pages->addHookAfter('save', $this, 'setTrigger');

    $timeInterval = self::getTimeInterval();
    $hook = "LazyCron::$timeInterval";

    $this->addHook($hook, $this, 'triggerEvent');
  }

  public function setTrigger(HookEvent $event) {
    $page = $event->arguments(0);

    // Do nothing for system pages
    if ($page->template->flags === Template::flagSystem) return;
    // Do nothing if page is not public
    if (!$page->isPublic) return;

    // Do nothing if trigger is already active
    if ($this->trigger == true) return;

    $data = $this->modules->getConfig($this->className);
    $data['trigger'] = 1;
    $this->modules->saveConfig($this->className, $data);

    wire('log')->save('github-repository-dispatch', 'Trigger activated');
  }

  public function triggerEvent() {
    $timeInterval = self::getTimeInterval();

    // Return if module is disabled
    if ($this->disabled) {
      wire('log')->save('github-repository-dispatch', "Trigger is disabled");
      return;
    }

    // Return if trigger is not active
    if ($this->trigger != true) {
      wire('log')->save('github-repository-dispatch', "Cronjob ($timeInterval) ran, but trigger was not active");
      return;
    }

    // Return if requirements are not met
    if (!$this->endpoint || !$this->personalAccessToken || !$this->eventType) {
      wire('log')->save('github-repository-dispatch', 'Requirements not met. Please check module configuration');
      return;
    }

    $http = new WireHttp();

    $http->setHeaders(array(
      'Accept' => 'application/vnd.github+json',
      'Authorization' => 'Bearer ' . $this->personalAccessToken
    ));

    // TODO: convert textarea to associative array
    // $clientPayload = $this->clientPayload;

    $data = array(
      'event_type' => $this->eventType,
      // 'client_payload' => $clientPayload
    );

    $response = $http->post($this->endpoint, json_encode($data));

    if ($response !== false) {
      $status = $http->getHttpCode();
      if ($status === 204) {
        wire('log')->save('github-repository-dispatch', "SUCCESS: Event '$this->eventType' at $timeInterval triggered");
      } else {
        wire('log')->save('github-repository-dispatch', 'ERROR: ' . $this->sanitizer->entities($response));
      }
    } else {
      wire('log')->save('github-repository-dispatch', 'ERROR: ' . $http->getError());
    }

    // Reset trigger in module config
    $data = $this->modules->getConfig($this->className);
    $data['trigger'] = 0;
    $this->modules->saveConfig($this->className, $data);
  }
}
