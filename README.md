MonadPHP
========

This is a basic Monad library for PHP.

Usage
=====

Values are "wrapped" in the monad via either the constructor: `new MonadPHP\Identity($value)` or the `unit()` method on an existing instance: `$monad->unit($value);`

Functions can be called on the wrapped value using `bind()`:

    use MonadPHP\Identity;
    $monad = new Identity(1);
    $monad->bind(function($value) { var_dump($value); });
    // Prints int(1)

All calls to bind return a new monad instance wrapping the return value of the function.

     use MonadPHP\Identity;
    $monad = new Identity(1);
    $monad->bind(function($value) {
            return 2 * $value;
        })->bind(function($value) { 
            var_dump($value); 
        });
    // Prints int(2)

Additionally, "extracting" the raw value is supported as well (since this is PHP and not a pure functional language)...

    use MonadPHP\Identity;
    $monad = new Identity(1);
    var_dump($monad->extract());
    // Prints int(1)

Maybe Monad
===========

One of the first useful monads, is the Maybe monad. The value here is that it will only call the callback provided to `bind()` if the value it wraps is not `null`.

    use MonadPHP\Maybe;
    $monad = new Maybe(1);
    $monad->bind(function($value) { var_dump($value); });
    // prints int(1)

    $monad = new Maybe(null);
    $monad->bind(function($value) { var_dump($value); });
    // prints nothing (callback never called)...

The included Chain monad does the same thing, but providing a short-cut implementation for objects:

    use MonadPHP\Chain;
    $monad = new Chain($someChainableObject);
    $obj = $monad->call1()->call2()->nonExistantMethod()->call4()->extract();
    var_dump($obj);
    // null

This can prevent errors when used with chaining...

List Monad
==========

This abstracts away the concept of a list of items (an array):

    use MonadPHP\ListMonad;
    $monad = new ListMonad(array(1, 2, 3, 4));
    $doubled = $monad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(2, 4, 6, 8)

Note that the passed in function gets called once per value, so it only ever deals with a single element, never the entire array...

It also works with any `Traversable` object (like iterators, etc). Just be aware that returning the new monad that's wrapped will alwyas become an array...

Composition
===========

These Monads can be composed together to do some really useful things:

    use MonadPHP\ListMonad;
    use MonadPHP\Maybe;

    $monad = new ListMonad(array(1, 2, 3, null, 4));
    $newMonad = $monad->bind(function($value) { return new Maybe($value); });
    $doubled = $newMonad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(2, 4, 6, null, 8)

Or, what if you want to deal with multi-dimensional arrays?

    use MonadPHP\ListMonad;
    $monad = new ListMonad(array(array(1, 2), array(3, 4), array(5, 6)));
    $newMonad = $monad->bind(function($value) { return new ListMonad($value); });
    $doubled = $newMonad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(array(2, 4), array(6, 8), array(10, 12))

There also exist helper constants on each of the monads to get a callback to the `unit` method:

    $newMonad = $monad->bind(Maybe::unit);
    // Does the same thing as above 
