<?php

use Pest\TestSuite;
use PHPUnit\Framework\ExpectationFailedException;

beforeEach(function () {
    $this->snapshotable = <<<'HTML'
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Snapshot</h1>
                </div>
            </div>
        </div>
    HTML;
});

test('pass', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    expect($this->snapshotable)->toMatchSnapshot();
});

test('pass with `__toString`', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    $object = new class($this->snapshotable)
    {
        public function __construct(protected string $snapshotable)
        {
        }

        public function __toString()
        {
            return $this->snapshotable;
        }
    };

    expect($object)->toMatchSnapshot()->toMatchSnapshot();
});

test('pass with `toString`', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    $object = new class($this->snapshotable)
    {
        public function __construct(protected string $snapshotable)
        {
        }

        public function toString()
        {
            return $this->snapshotable;
        }
    };

    expect($object)->toMatchSnapshot()->toMatchSnapshot();
});

test('pass with dataset', function ($data) {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);
    [$filename] = TestSuite::getInstance()->snapshots->get($this, $this->snapshotable);

    expect($filename)->toEndWith('pass_with_dataset_with_data_set____my_datas_set_value______my_datas_set_value__.snap')
        ->and($this->snapshotable)->toMatchSnapshot();
})->with(['my-datas-set-value']);

describe('within describe', function () {
    test('pass with dataset', function ($data) {
        TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);
        [$filename] = TestSuite::getInstance()->snapshots->get($this, $this->snapshotable);

        expect($filename)->toEndWith('pass_with_dataset_with_data_set____my_datas_set_value______my_datas_set_value__.snap')
            ->and($this->snapshotable)->toMatchSnapshot();
    });
})->with(['my-datas-set-value']);

test('failures', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    expect('contain that does not match snapshot')->toMatchSnapshot();
})->throws(ExpectationFailedException::class, 'Failed asserting that two strings are identical.');

test('failures with custom message', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    expect('contain that does not match snapshot')->toMatchSnapshot('oh no');
})->throws(ExpectationFailedException::class, 'oh no');

test('not failures', function () {
    TestSuite::getInstance()->snapshots->save($this, $this->snapshotable);

    expect($this->snapshotable)->not->toMatchSnapshot();
})->throws(ExpectationFailedException::class);