<?php

test('the home page redirects to notes', function () {
    $this->get('/')->assertRedirect('/notes');
});
