<?php

abstract class ErrorCodes
{
  const ERROR = 2000;
  const NO_KEYS = 2007;
  const AUTH_ERROR = 2008;
  const RTI_SERVER_AUTH_ERROR = 2009;
  const SCRIPT_NOT_INSTALLED = 2010;
  const RTI_SERVER_NO_RESPONSE = 2011;
  const RTI_SERVER_MISSING_THREAT_TYPE = 2012;
  const INCORRECT_SECRET_KEY = 2013;
  const RTI_SERVER_INCORRECT_COOKIE = 2014;
  const RTI_SERVER_EMPTY_RESPONSE_FORMAT = 2015;
  const RTI_SERVER_INVALID_RESPONSE_FORMAT = 2016;
  const RTI_SERVER_MISSING_COOKIE = 2017;
  const USER_MOVED_FROM_FORM_PAGE_MIDVALIDATION = 2018;
  const PLUGIN_INSTALL = 2019;
  const PLUGIN_REMOVE = 2020;
  const PLUGIN_START_AUTHENTICATE = 2021;
  const PLUGIN_SUCCESS_AUTHENTICATE = 2022;
  const NO_CLIENT_IP = 2023;
  const WHITELIST_TRACK = 2024;
  const TAG_VALIDATION_ERROR = 2025;
}

abstract class AllowedCodes
{
  const VALID = 0;
  const FREQUENCY_CAPPING = 4;
  const ABNORMAL_RATE_LIMIT = 5;
  const DATA_CENTER = 13;
  const VPN = 14;
  const PROXY = 15;
  const CLICK_HIJACKING = 17;
  const GOOD_BOT = 19;
  const CRAWLERS = 20;
}

abstract class Urls
{
  const RTI_SERVER_EUROPE = 'https://rti-eu-west-1.cheqzone.com/v1/realtime-interception';
  const RTI_LOGGER = 'https://rtilogger.production.cheq-platform.com/';
  const BOTZAPPING = 'https://botzapping.eu.cheq-platform.com';
  const CHEQ_TAG = 'obseu.bzcclandlord.com';
  const CLICKCEASE = 'https://www.clickcease.com';
  const CLICKCEASE_MONITORING = 'https://monitor.clickcease.com/stats';
  const CLICKCEASE_BOTZAPPING = 'https://api.clickcease.com/dashboard/api/BotZappingDomain';
  const HTTPS = 'https://';
}

abstract class HTTPCode
{
  const SUCCESS = 200;
  const BAD_REQUEST = 400;
}

abstract class ResponseMessage
{
  const INVALID_KEYS = 'Error: Please make sure you entered the right keys. Those can be found on your ClickCease Bot Zapping dashboard.';
  const INVALID_DOAMIN = 'Error: Domain key is invalid';
  const SUCCESS = 'success';
}

abstract class DomainState
{
  const BZ_PLUGIN_ACTIVATED = 2;
  const BZ_PLUGIN_DEACTIVATED = 4;
  const BZ_PLUGIN_UNINSTALLED = 5;
  const PLUGIN_ACTIVATED = 9;
  const PLUGIN_DEACTIVATED = 10;
}
