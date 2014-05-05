# Integrating composer and learning about namespaces

*Problem: Including a bunch of 3rd party PHP libraries in a project is a pain in the butt.*

Enter [Composer](https://getcomposer.org/), the new hotness for managing PHP libraries and classes. With a few simple lines of code like: 

    {
      "require": {
        "hipchat/hipchat-php": ">=1.0.0"
      },
      "autoload": {
        "psr-4": {"Logsite\\": "classes/"}
      }
    }

...I can load up a library that lets me send messages to a HipChat room. Unfortunately, when I followed the directions in the [Composer documentation and require'd their autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading), Logsite broke completely. That's because the Composer autoloader follows the [PSR-4 guidelines](http://www.php-fig.org/psr/psr-4/), part of which requires that all autoloaded classes be [namespaced](http://www.php.net/manual/en/language.namespaces.rationale.php).

Which it turns out is actually super easy. All you need to do is declare a namespace for your app's classes like `namespace: Logsite;` before you open your class declaration and you're all set. Then, when you're calling that class later on in your code, you instantiate it with the namespace: 

    $user = new Logsite\user();

3rd party libraries are the same, just be sure to use their namespace: 

    $hipchat = new HipChat\Hipchat();

There's a few big gotchas with all of this. The big one I ran into at first was that classes that implement an internal PHP interface, such as SessionHandlerInterface need to be prefixed with a \, to denote that you're calling from the global namespace: 

    class session implements \SessionHandlerInterface

Second, instantiating one of your classes from inside another one doesn't need to use your namespace. Something like

    $user = new user();

will work just fine if you're right in the middle of another class that's in the same namespace. 

That doesn't work for classes outside your namespace. In fact, you'll have to specifically state that you're going outside your namespace, like so: 

    namespace Logsite;

    use HipChat\HipChat as Hipchat;

    class contact { ... }

And then you can just call HipChat as you would before namespaces were a thing: 

    $hipchat = new HipChat();

So why should you go to all this trouble just to use Composer? The bottom line is that it makes using 3rd party libraries a whole hell of a lot easier to manage. Instead of downloading, unzipping and installing a bunch of PHP files, you can let Composer handle all of that. As a bonus, anyone else who wants to download and run your application can install the same libraries you used, so you won't have to deal with dependency-related bugs.

Just be sure to put your composer install directory in your .gitignore. There's no need to copy a bunch of redundant libraries into your git repository.