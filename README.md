# Laravel advertising manager.

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-ad-director?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-ad-director)](https://packagist.org/packages/yaroslawww/laravel-ad-director)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-ad-director)](https://packagist.org/packages/yaroslawww/laravel-ad-director)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-ad-director/?branch=master)

Package to help you add advertising to your site.

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-ad-director
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="AdDirector\ServiceProvider" --tag="config"
```

## Supported services

- [Google Publisher Tags](https://developers.google.com/publisher-tag/reference)
- to add new services, please send PR.

## Usage

If you use Google Publisher Tags, and use repeated sizes - you can easily configure global sizes with size mapping in
any service provider - as GPT is singleton.

```injectablephp
use AdDirector\Facades\AdDirector;
use AdDirector\GPT\SizeMap;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        AdDirector::gpt()
        // Add size with mapping example
        ::addSize('leaderboard', new \AdDirector\GPT\Size(
            // Allowed sizes
            [[728, 90], [320, 50]],
            // Responsive size map
            (new SizeMap())
                          // Tablet
                          ->addSize([1024, 768], [728, 90])
                          ->addSize([980, 690], [728, 90])
                          // Desktop
                          ->addSize([1050, 200], [728, 90])
                          // Mobile
                          ->addSize([640, 480], [320, 50])
                          // Any size
                          ->addSize([0, 0], [320, 50])
        ))
        // Add static size without responsive
        ::addSize('medium_rectangle', new \AdDirector\GPT\Size([300, 250]));
    }
}
```

On page controller you can configure set of advert locations.

```injectablephp
use AdDirector\Facades\AdDirector;
use AdDirector\GPT\Slot;

class FrontpageController extends Controller
{
    public function __invoke()
    {
        AdDirector::gpt()
                  ->addLocation(Slot::make(
                      '/1234567/FOO_Leaderboard',
                      // Size name from global config
                      'leaderboard',
                      // Optional, if not set will be generated automatically
                      'div-gpt-ad-1234567890001-0'
                      // Location identifier, if not set will be used slot's adUnitPath
                  ), 'header-ads')
                  
                  ->addLocation(Slot::make(
                      '/1234567/FOO_Medium',
                      'medium_rectangle',
                  ), 'medium-ads')
                  
                  ->addLocation(Slot::make(
                      '/1234567/BAR_Leaderboard',
                     // Manually set custom size
                     new \AdDirector\GPT\Size(
                         [[728, 90], [320, 50]],
                         (new SizeMap())
                            ->addSize([1024, 768], [728, 90])
                            ->addSize([640, 480], [320, 50])
                            ->addSize([0, 0], [320, 50])
                     ),
                  ), 'footer-ads');

        return view('frontpage');
    }
}
```

Now there time to add scripts to template

```blade
<head>
    {!! \AdDirector\Facades\AdDirector::configurationScript() !!}
</head>
<body>
    <div id="header">
        {!! \AdDirector\Facades\AdDirector::adLocation('header-ads') !!}
    </div>
    <div id="content">
        <div>Content</div>
        <div>
            {!! \AdDirector\Facades\AdDirector::adLocation('medium-ads-ads') !!}
        </div>
        <div>Content</div>
    </div>
    <div id="footer">   
        {!! \AdDirector\Facades\AdDirector::adLocation('footer-ads') !!}
    </div>
    <script>
        {{-- There possible to add any js confitions, timeout, etc.. --}}
        {!! \AdDirector\Facades\AdDirector::displayScript() !!}
    </script>
</body>
</html>
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/) 
