<?php

$config = array(
  'disabled' => array(
    'type' => 'checkbox',
    'label' => 'Trigger disabled',
    'notes' => 'If checked, the event will not be triggered. This is helpful in a localhost/debug environment.'
  ),

  'trigger' => array(
    'type' => 'checkbox',
    'label' => 'Execute trigger on next page save',
    'notes' => 'If checked, the event will be triggerd on the next run. This value gets checked automatically as soon as a page is saved.'
  ),

  'endpoint' => array(
    'type' => 'text',
    'label' => 'Endpoint',
    'placeholder' => 'https://api.github.com/repos/{OWNER}/{REPO}/dispatches',
    'required' => true,
    'notes' => ''
  ),

  'personalAccessToken' => array(
    'type' => 'text',
    'label' => 'Personal access token',
    'description' => '',
    'required' => true,
    'notes' => 'https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token'
  ),

  'eventType' => array(
    'type' => 'text',
    'label' => 'Event type',
    'description' => '',
    'required' => true,
    'maxlength' => 100,
    'notes' => 'https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#repository_dispatch'
  ),

  // 'clientPayload' => array(
  //   'type' => 'textarea',
  //   'label' => 'Client payload',
  //   'placeholder' => 'unit=false',
  //   'description' => '',
  //   'required' => false,
  //   'notes' => 'One key=value per line'
  // ),

  'timeInterval' => array(
    'type' => 'text',
    'label' => 'How often do you want to trigger the event?',
    'description' => '',
    'placeholder' => 'everyDay',
    'required' => false,
    'notes' => 'Example values "everyMinute", "every2Hours", etc. Falls back to "everyDay" if omitted. Reference of usable values: https://processwire.com/docs/more/lazy-cron/'
  ),
);
