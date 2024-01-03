<?php

namespace App\EventSource\Base;

use App\Enum\Language;

abstract class AbstractEventSource
{
    protected Language $language;

    protected int $month;

    protected int $day;

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;
        return $this;
    }

}
