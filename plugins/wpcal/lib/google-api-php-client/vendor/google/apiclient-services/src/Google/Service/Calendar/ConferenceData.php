<?php

namespace WPCal\GoogleAPI;

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
class Google_Service_Calendar_ConferenceData extends \WPCal\GoogleAPI\Google_Collection
{
    protected $collection_key = 'entryPoints';
    public $conferenceId;
    protected $conferenceSolutionType = 'WPCal\\GoogleAPI\\Google_Service_Calendar_ConferenceSolution';
    protected $conferenceSolutionDataType = '';
    protected $createRequestType = 'WPCal\\GoogleAPI\\Google_Service_Calendar_CreateConferenceRequest';
    protected $createRequestDataType = '';
    protected $entryPointsType = 'WPCal\\GoogleAPI\\Google_Service_Calendar_EntryPoint';
    protected $entryPointsDataType = 'array';
    public $notes;
    protected $parametersType = 'WPCal\\GoogleAPI\\Google_Service_Calendar_ConferenceParameters';
    protected $parametersDataType = '';
    public $signature;
    public function setConferenceId($conferenceId)
    {
        $this->conferenceId = $conferenceId;
    }
    public function getConferenceId()
    {
        return $this->conferenceId;
    }
    /**
     * @param Google_Service_Calendar_ConferenceSolution
     */
    public function setConferenceSolution(\WPCal\GoogleAPI\Google_Service_Calendar_ConferenceSolution $conferenceSolution)
    {
        $this->conferenceSolution = $conferenceSolution;
    }
    /**
     * @return Google_Service_Calendar_ConferenceSolution
     */
    public function getConferenceSolution()
    {
        return $this->conferenceSolution;
    }
    /**
     * @param Google_Service_Calendar_CreateConferenceRequest
     */
    public function setCreateRequest(\WPCal\GoogleAPI\Google_Service_Calendar_CreateConferenceRequest $createRequest)
    {
        $this->createRequest = $createRequest;
    }
    /**
     * @return Google_Service_Calendar_CreateConferenceRequest
     */
    public function getCreateRequest()
    {
        return $this->createRequest;
    }
    /**
     * @param Google_Service_Calendar_EntryPoint
     */
    public function setEntryPoints($entryPoints)
    {
        $this->entryPoints = $entryPoints;
    }
    /**
     * @return Google_Service_Calendar_EntryPoint
     */
    public function getEntryPoints()
    {
        return $this->entryPoints;
    }
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    /**
     * @param Google_Service_Calendar_ConferenceParameters
     */
    public function setParameters(\WPCal\GoogleAPI\Google_Service_Calendar_ConferenceParameters $parameters)
    {
        $this->parameters = $parameters;
    }
    /**
     * @return Google_Service_Calendar_ConferenceParameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }
    public function getSignature()
    {
        return $this->signature;
    }
}
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
\class_alias('WPCal\\GoogleAPI\\Google_Service_Calendar_ConferenceData', 'Google_Service_Calendar_ConferenceData', \false);
