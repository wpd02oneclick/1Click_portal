<?php
namespace App\Services;

use App\Models\Auth\User;
use App\Models\BasicModels\Departments;
use App\Models\BasicModels\OrderGenericType;
use App\Models\BasicModels\OrderIndustries;
use App\Models\BasicModels\OrderServices;
use App\Models\BasicModels\OrderVoices;
use App\Models\BasicModels\OrderWebsites;
use App\Models\BasicModels\OrderWritingStyles;
use App\Models\BasicModels\UserDesignations;
use App\Models\BasicModels\WriterSkills;

class Pre_Villages
{
    public function getDepartments()
    {
        return Departments::orderBy('id', 'DESC')->get();
    }
    public function getDesignations()
    {
        return UserDesignations::orderBy('id', 'DESC')->get();
    }
    public function getCoordinators()
    {
        return User::whereIn('Role_ID', [4, 5, 10])->get();
    }
    public function getOrderServices()
    {
        return OrderServices::orderBy('id', 'DESC')->get();
    }
    public function getOrderWebsites()
    {
        return OrderWebsites::orderBy('id', 'DESC')->get();
    }

    public function getWriterSkills()
    {
        return WriterSkills::orderBy('id', 'DESC')->get();
    }

    public function getWritingStyles()
    {
        return OrderWritingStyles::orderBy('id', 'DESC')->get();
    }

    public function getOrderVoices()
    {
        return OrderVoices::orderBy('id', 'DESC')->get();
    }

    public function getOrderIndustries()
    {
        return OrderIndustries::orderBy('id', 'DESC')->get();
    }

    public function getOrderGeneric()
    {
        return OrderGenericType::orderBy('id', 'DESC')->get();
    }
}
