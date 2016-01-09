/* global casper, require */

'use strict';

/* ----------------------------------------------------------
  Functions
---------------------------------------------------------- */

function click_link_or_alert(self, test, linkPath) {
    if (self.exists(linkPath)) {
        self.thenClick(linkPath);
    }
    else {
        test.comment('Link "' + linkPath + '" does not exists"');
        test.exit(1);
    }
}

function test_correct_page(test, bodyClass) {
    test.assertExists('body.' + bodyClass, 'We are on the correct page');
}

/* ----------------------------------------------------------
  Config
---------------------------------------------------------- */

var fs = require('fs'),
    config = JSON.parse(fs.read('config.json')),
    page = config.local_url;

/* ----------------------------------------------------------
  Scenario
---------------------------------------------------------- */

casper.test.begin('Testing home page', 0, function suite(test) {

    casper.start(page, function() {
        // Display title
        test.comment('Open page with title "' + this.getTitle() + '"');
        // Title contains ... title
        test.assertTitleMatch(/site/, 'Title is what we\'d expect');
        // Check that body has class 'home'
        test_correct_page(test, 'home');
        // Click on the first link
        click_link_or_alert(this, test, 'a');
    });

    casper.run(function() {
        test.done();
    });
});
