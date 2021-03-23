<?php


namespace IgorPC\TrackPackage\Controllers;


use IgorPC\TrackPackage\Models\TrackPackage;

class TrackPackageController extends Controller
{
    private TrackPackage $trackPackage;

    public function __construct()
    {
        $this->trackPackage = new TrackPackage();
    }

    protected function ways($resource)
    {
        $ways = [
            'track_package' => ['show', 'none'],
        ];

        return $ways[$resource];
    }

    public function show($data)
    {
        return $this->trackPackage->show($data);
    }
}