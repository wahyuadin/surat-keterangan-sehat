<?php

namespace App\Observers;

use App\Models\Agent;

class AgentObserver
{
    public function updating(Agent $agent)
    {
        $agent->auditCustomEvent('updated');
    }

    public function deleting(Agent $agent)
    {
        $agent->auditCustomEvent('deleted');
    }

    public function restoring(Agent $agent)
    {
        $agent->auditCustomEvent('restored');
    }
}
