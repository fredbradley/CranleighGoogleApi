<?php


namespace fredbradley\CranleighGoogleApi\Maps;

class GoogleActivity
{
//    public $ip_address;
    private function getParameterKeys()
    {
        return [
            'identifier',
            'location',
            'device_type',
            'ip_address',
            'organizer_email',
            'meeting_code',
            'is_external',
        ];
    }


    public function __construct(\Google_Service_Reports_Activity $input)
    {
        $new_event = [];
        foreach ($input->events as $key => $event) {
            foreach ($event->parameters as $parameter) {
                if (in_array($parameter->name, $this->getParameterKeys())) {
                    $new_event[ $parameter->name ] = $parameter->value;
                }
            }
        }
        if (! isset($new_event['ip_address'])) {
            $new_event['ip_address'] = null;
        }

        //unset($input->events[0]);
//        $this->things = $input;
        $this->time = $input->id->time;
        $this->uniqueQualifier = $input->id->uniqueQualifier;
        $this->actor = $input->actor;
        $this->parameters = $new_event;
    }
}
