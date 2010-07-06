<?php

/**
 *
 * Generic Config Loading Framework for PHP Scripts
 * Copyright (C) 2004-2005, Three Wise Men Software Development and Consulting
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * <b>General Usage Information</b>
 *
 * Instantiate a new TWM_Config object; by default it loads configuration
 * data from a file called <i>config.ini</i>.
 *
 * If you want it to load from a different file, specify the filename in the
 * constructor.
 *
 * Sample config.ini file:
 *
 * <code>
 * [default]
 * title = My Screen Title
 *
 * [mail]
 * smtp_server = 10.1.2.2
 *
 * [db]
 * host = localhost
 * database = test
 * username = dbuser
 * password = dbpass
 * </code>
 *
 * Sample usage:
 *
 * <code>
 * $CFG = new TWM_Config();
 * echo "<h1>".$CFG->data['default']['title']."</h1>";
 * </code>
 *
 * @version 1.0.0
 * @copyright 2004-2005, Three Wise Men Software Development and Consulting
 *
 */

/**
 * Main class for Configuration framework
 */
class TWM_Config {

  var $filename;
  var $data = array();

  function TWM_Config($filename = "config.ini") {
    $this->filename = $filename;
    $fc=file($this->filename);

    $section = 'default';
    $data[$section] = array();

    foreach($fc as $line) {
      $heading = $this->getheading($line);
      if ($heading) {
        $section = $heading;
        if (!array_key_exists($section, $data)) $data[$section] = array();
      } else if ($this->canprocess($line)) {
        $keyvalue=explode("=", $line);
        if (count($keyvalue) == 2) {
          $key = trim(strtolower($keyvalue[0]));
          $value = trim($keyvalue[1]);
          $this->data[$section][$key] = $value;
        }
      }
    }
  }

  function startswith($line, $char) {
    $pos = strpos(trim($line), $char);
    if (is_numeric($pos)) {
      if ($pos == 0) {
        return true;
      }
    }
    return false;
  }

  function iscomment($line) {
    $iscomment = false;
    if (TWM_Config::startswith($line, '#')) $iscomment = true;
    if (TWM_Config::startswith($line, ';')) $iscomment = true;
    return $iscomment;
  }

  function isblank($line) {
    if (strlen(trim($line))>0) return false;
    return true;
  }

  function canprocess($line) {
    $canprocess = true;
    if (TWM_Config::iscomment($line)) $canprocess = false;
    if (TWM_Config::isblank($line)) $canprocess = false;
    return $canprocess;
  }

  function getheading($line) {
    $matches = array();
    preg_match("/^\[(\w+)\]\$/", trim($line), $matches);
    if (count($matches)>=2) {
        return $matches[1];
    } else {
        return null;
    }
  }
}

?>