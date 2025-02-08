<?php

uses(Tests\TestCase::class);

test('avoid dd, dump and die')
    ->expect(['dd', 'dump', 'die'])
    ->not->toBeUsed();
