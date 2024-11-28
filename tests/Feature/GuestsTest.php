<?php

it('guest url shows table img', function () {
    $response = $this->get('/guest');

    $response->assertStatus(200);
});
