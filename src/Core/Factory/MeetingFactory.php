<?php 

namespace App\Core\Factory;

use App\Entity\Meeting;
use Throwable;

final class MeetingFactory extends FactoryAbstract
{
    public function __construct(
    ) {
        $this->required = ['owner', 'name', 'date', 'participants_limit'];
    }

    public function create(array $arguments = []): Meeting
    {
        try {
            return $this->make(
                object: new Meeting, arguments: $arguments
            );
        } catch (Throwable $e) {
            throw $e;
        }
    }
}