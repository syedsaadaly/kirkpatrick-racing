<?php

namespace App\Http\Controllers\Front;

use App\Helpers\CmsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $pageData = CmsHelper::getPageData('home');
        return view('front.index', compact('pageData'));
    }

    public function about()
    {
        $pageData = CmsHelper::getPageData('about-us');
        $homePage = CmsHelper::getPageData('home');
        return view('front.about', compact('pageData', 'homePage'));
    }

    public function contact()
    {
        $pageData = CmsHelper::getPageData('contact-us');
        $homePage = CmsHelper::getPageData('home');
        return view('front.contact', compact('pageData', 'homePage'));
    }

    public function electricBike()
    {
        return view('front.electric-bike');
    }

    public function gallery()
    {
        return view('front.gallery');
    }

    public function services()
    {
        return view('front.services');
    }

    public function privacyPolicy()
    {
        return view('front.privacy-policy');
    }

    public function termsOfService()
    {
        return view('front.terms-of-service');
    }
}
