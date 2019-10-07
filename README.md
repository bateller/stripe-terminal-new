## stripe-terminal: A simple credit card terminal for Stripe.com

stripe-terminal is a single form that allows anyone with a [Stripe.com](https://stripe.com/) account to submit credit card charges from any web browser.

This Repository was originally based on [@wlrs](https://github.com/wlrs/stripe-terminal)' original Stripe-Terminal repository at [https://github.com/wlrs/stripe-terminal](https://github.com/wlrs/stripe-terminal)

My updated version (over 4 years old now) of [@wlrs](https://github.com/wlrs/stripe-terminal)' terminal can be found here: [https://github.com/bateller/stripe-terminal](https://github.com/bateller/stripe-terminal) (This should still work with the current stripe-php SDK, but uses an older Stripe.js)

This Repository is a fully revamped update to work with Stripe's latest API located at: [https://github.com/stripe/stripe-php](https://github.com/stripe/stripe-php) as well as Stripe.js (version 3.0)


### Features

 * Simple javascript validation of data (No JQuery needed anymore)
 * Stripe.js token generation (credit card data doesn't touch your server)
 * Mobile browser friendly


### Requirements

 * Stripe account 
 * PHP web environment serving pages via https

Stripe accounts are free but require a US-based checking account to actually create charges. Test charges can be made from a page served over http.


### Installation

Just clone this repo into a clean directory. `stripe-php` is included as a submodule so using the `--recursive` flag will get you everything:

	git clone --recursive git://github.com/bateller/stripe-terminal-new.git .

Then just set `$key_publishable` and `$key_secret` in index.php and you're good to go.

Stripe.com provides "test" and "live" API keys, you should start with your test keys and refer to the [Stripe Testing FAQ](https://stripe.com/docs/testing).


### Demo

[https://wbat.net/stripe-terminal-demo2/](https://wbat.net/stripe-terminal-demo2/)

The demo is running in test mode, but please don't submit real credit card information.
