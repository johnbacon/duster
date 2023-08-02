<?php

it('lints without issues', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/WithoutIssues'),
    ]);

    expect($statusCode)->toBe(0)
        ->and($output);
});

it('lints with TLint', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/TlintFixableIssues'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`');
});

it('lints with TLint when path contains space', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/TlintFixableIssues/file with spaces.blade.php'),
        '--using' => 'tlint',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`');
});

it('lints with TLint with checkstyle output', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/TlintFixableIssues'),
        '--format' => 'checkstyle',
    ]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('message="! Put a space between blade control structure names and the opening paren:`@if(`')
        ->toContain('</checkstyle>');
});

it('only lints with TLint', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/TlintFixableIssues'),
        '--using' => 'tlint',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`')
        ->not->toContain('Linting using PHP_CodeSniffer')
        ->not->toContain('Linting using PHP CS Fixer')
        ->not->toContain('Linting using Pint');
});

it('lints with PHP_CodeSniffer', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferFixableIssues'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP_CodeSniffer')
        ->toContain('Class name doesn\'t match filename');
});

it('lints with PHP_CodeSniffer with checkstyle output', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferFixableIssues'),
        '--format' => 'checkstyle',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('message="Class name doesn\'t match filename;')
        ->toContain('</checkstyle>');
});

it('only lints with PHP_CodeSniffer', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferFixableIssues'),
        '--using' => 'phpcs',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP_CodeSniffer')
        ->toContain('Class name doesn\'t match filename')
        ->not->toContain('Linting using TLint')
        ->not->toContain('Linting using PHP CS Fixer')
        ->not->toContain('Linting using Pint');
});

it('lints with PHP CS Fixer', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCsFixerFixableIssues'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP CS Fixer')
        ->toContain('Tighten/custom');
});

// TODO: Write JSON tests for all linters

it('lints with PHP CS Fixer with checkstyle output', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCsFixerFixableIssues'),
        '--format' => 'checkstyle',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('message="Found violation(s) of type:')
        ->toContain('</checkstyle>');
});

it('only lints with PHP CS Fixer', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCsFixerFixableIssues'),
        '--using' => 'phpcsfixer',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP CS Fixer')
        ->toContain('Tighten/custom')
        ->not->toContain('Linting using TLint')
        ->not->toContain('Linting using PHP_CodeSniffer')
        ->not->toContain('Linting using Pint');
});

it('lints with Pint', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PintFixableIssues'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using Pint')
        ->toContain('concat_space');
});

it('lints with Pint with checkstyle output', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PintFixableIssues'),
        '--format' => 'checkstyle',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('message="Found violation(s) of type: concat_space')
        ->toContain('</checkstyle>');
});

it('only lints with Pint', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PintFixableIssues'),
        '--using' => 'pint',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using Pint')
        ->toContain('concat_space')
        ->not->toContain('Linting using TLint')
        ->not->toContain('Linting using PHP_CodeSniffer')
        ->not->toContain('Linting using PHP CS Fixer');
});

it('only lints with both TLint and Pint', function () {
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/MultipleFixableIssues'),
        '--using' => 'tlint,pint',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`')
        ->toContain('Linting using Pint')
        ->toContain('concat_space')
        ->not->toContain('Linting using PHP_CodeSniffer')
        ->not->toContain('Linting using PHP CS Fixer');
});

it('lints multiple provided files', function () {
    [$statusCode, $output] = run('lint', [
        'path' => [
            base_path('tests/Fixtures/MultipleFixableIssues/file.blade.php'),
            base_path('tests/Fixtures/MultipleFixableIssues/file.php'),
        ],
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`')
        ->toContain('Linting using Pint')
        ->toContain('concat_space');
});
