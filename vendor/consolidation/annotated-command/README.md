# Consolidation\AnnotatedCommand

Initialize Symfony Console commands from annotated command class methods.

[![Travis CI](https://travis-ci.org/consolidation/annotated-command.svg?branch=master)](https://travis-ci.org/consolidation/annotated-command)
[![Windows CI](https://ci.appveyor.com/api/projects/status/c2c4lcf43ux4c30p?svg=true)](https://ci.appveyor.com/project/greg-1-anderson/annotated-command)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/consolidation/annotated-command/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/consolidation/annotated-command/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/consolidation/annotated-command/badge.svg?branch=master)](https://coveralls.io/github/consolidation/annotated-command?branch=master) 
[![License](https://poser.pugx.org/consolidation/annotated-command/license)](https://packagist.org/packages/consolidation/annotated-command)

## Component Status

Currently in use in [Robo](https://github.com/consolidation/Robo) (1.x+), [Drush](https://github.com/drush-ops/drush) (9.x+) and [Terminus](https://github.com/pantheon-systems/terminus) (1.x+).

## Motivation

Symfony Console provides a set of classes that are widely used to implement command line tools. Increasingly, it is becoming popular to use annotations to describe the characteristics of the command (e.g. its arguments, options and so on) implemented by the annotated method.

Extant commandline tools that utilize this technique include:

- [Robo](https://github.com/consolidation/Robo)
- [wp-cli](https://github.com/wp-cli/wp-cli)
- [Pantheon Terminus](https://github.com/pantheon-systems/terminus)

This library provides routines to produce the Symfony\Component\Console\Command\Command from all public methods defined in the provided class.

**Note** If you are looking for a very fast way to write a Symfony Console-base command-line tool, you should consider using [Robo](https://github.com/consolidation/Robo), which is built on top of this library, and adds additional conveniences to get you going quickly. See [Using Robo as a Framework](https://github.com/consolidation/Robo/docs/framework.md).  It is possible to use this project without Robo if desired, of course.

## Library Usage

This is a library intended to be used in some other project.  Require from your composer.json file:
```
    "require": {
        "consolidation/annotated-command": "~2"
    },
```

## Example Annotated Command Class
The public methods of the command class define its commands, and the parameters of each method define its arguments and options. The command options, if any, are declared as the last parameter of the methods. The options will be passed in as an associative array; the default options of the last parameter should list the options recognized by the command.

The rest of the parameters are arguments. Parameters with a default value are optional; those without a default value are required.
```php
class MyCommandClass
{
    /**
     * This is the my:cat command
     *
     * This command will concatenate two parameters. If the --flip flag
     * is provided, then the result is the concatenation of two and one.
     *
     * @command my:cat
     * @param integer $one The first parameter.
     * @param integer $two The other parameter.
     * @option arr An option that takes multiple values.
     * @option flip Whether or not the second parameter should come first in the result.
     * @aliases c
     * @usage bet alpha --flip
     *   Concatenate "alpha" and "bet".
     */
    public function myCat($one, $two, $options = ['flip' => false])
    {
        if ($options['flip']) {
            return "{$two}{$one}";
        }
        return "{$one}{$two}";
    }
}
``` 
## Option Default Values

The `$options` array must be an associative array whose key is the name of the option, and whose value is one of:

- The boolean value `false`, which indicates that the option takes no value.
- A **string** containing the default value for options that may be provided a value, but are not required to.
- The special value InputOption::VALUE_REQUIRED, which indicates that the user must provide a value for the option whenever it is used.
- The special value InputOption::VALUE_OPTIONAL, which produces the following behavior:
  - If the option is given a value (e.g. `--foo=bar`), then the value will be a string.
  - If the option exists on the commandline, but has no value (e.g. `--foo`), then the value will be `true`.
  - If the option does not exist on the commandline at all, then the value will be `null`.
  - If the user explicitly sets `--foo=0`, then the value will be converted to `false`.
  - LIMITATION: If any Input object other than ArgvInput (or a subclass thereof) is used, then the value will be `null` for both the no-value case (`--foo`) and the no-option case. When using a StringInput, use `--foo=1` instead of `--foo` to avoid this problem.
- The special value `true` produces the following behavior:
  - If the option is given a value (e.g. `--foo=bar`), then the value will be a string.
  - If the option exists on the commandline, but has no value (e.g. `--foo`), then the value will be `true`.
  - If the option does not exist on the commandline at all, then the value will also be `true`.
  - If the user explicitly sets `--foo=0`, then the value will be converted to `false`.
  - If the user adds `--no-foo` on the commandline, then the value of `foo` will be `false`.
- An empty array, which indicates that the option may appear multiple times on the command line.

No other values should be used for the default value. For example, `$options = ['a' => 1]` is **incorrect**; instead, use `$options = ['a' => '1']`. Similarly, `$options = ['a' => true]` is unsupported, or at least not useful, as this would indicate that the value of `--a` was always `true`, whether or not it appeared on the command line.

Default values for options may also be provided via the `@default` annotation. See hook alter, below.

## Hooks

Commandfiles may provide hooks in addition to commands. A commandfile method that contains a @hook annotation is registered as a hook instead of a command.  The format of the hook annotation is:
```
@hook type target
```
The hook **type** determines when during the command lifecycle this hook will be called. The available hook types are described in detail below.

The hook **target** specifies which command or commands the hook will be attached to. There are several different ways to specify the hook target.

- The command's primary name (e.g. `my:command`) or the command's method name (e.g. myCommand) will attach the hook to only that command.
- An annotation (e.g. `@foo`) will attach the hook to any command that is annotated with the given label.
- If the target is omitted, then the hook will be attached to every command defined in the same class as the hook implementation.

There are ten types of hooks supported:

- Command Event (Symfony)
- Option
- Initialize (Symfony)
- Interact (Symfony)
- Validate
- Command
- Process
- Alter
- Status
- Extract
- On-event
- Replace Command

Most of these also have "pre" and "post" varieties, to give more flexibility vis-a-vis hook ordering (and for consistency). Within one type of hook, the running order is undefined and not guaranteed. Note that many validate, process and alter hooks may run, but the first status or extract hook that successfully returns a result will halt processing of further hooks of the same type.

Each hook has an interface that defines its calling conventions; however, any callable may be used when registering a hook, which is convenient if versions of PHP prior to 7.0 (with no anonymous classes) need to be supported.

### Command Event Hook

The command-event hook is called via the Symfony Console command event notification callback mechanism. This happens prior to event dispatching and command / option validation.  Note that Symfony does not allow the $input object to be altered in this hook; any change made here will be reset, as Symfony re-parses the object. Changes to arguments and options should be done in the initialize hook (non-interactive alterations) or the interact hook (which is naturally for interactive alterations).

### Option Event Hook

The option event hook ([OptionHookInterface](src/Hooks/OptionHookInterface.php)) is called for a specific command, whenever it is executed, or its help command is called. Any additional options for the command may be added here by calling the `addOption` method of the provided `$command` object. Note that the option hook is only necessary for calculating dynamic options. Static options may be added via the @option annotation on any hook that uses them. See the [Alter Hook](https://github.com/consolidation/annotated-command#alter-hook) documentation below for an example.
```
use Consolidation\AnnotatedCommand\AnnotationData;
use Symfony\Component\Console\Command\Command;

/**
 * @hook option some:command
 */
public function additionalOption(Command $command, AnnotationData $annotationData)
{
    $command->addOption(
        'dynamic',
        '',
        InputOption::VALUE_NONE,
        'Option added by @hook option some:command'
    );
}
```

### Initialize Hook

The initialize hook ([InitializeHookInterface](src/Hooks/InitializeHookInterface.php)) runs prior to the interact hook.  It may supply command arguments and options from a configuration file or other sources. It should never do any user interaction.

The [consolidation/config](https://github.com/consolidation/config) project (which is used in [Robo PHP](https://github.com/consolidation/robo)) uses `@hook init` to automatically inject values from `config.yml` configuration files for options that were not provided on the command line.
```
use Consolidation\AnnotatedCommand\AnnotationData;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @hook init some:command
 */
public function initSomeCommand(InputInterface $input, AnnotationData $annotationData)
{
    $value = $input->getOption('some-option');
    if (!$value) {
        $input->setOption('some-option', $this->generateRandomOptionValue());
    }
}
```

### Interact Hook

The interact hook ([InteractorInterface](src/Hooks/InteractorInterface.php)) runs prior to argument and option validation. Required arguments and options not supplied on the command line may be provided during this phase by prompting the user.  Note that the interact hook is not called if the --no-interaction flag is supplied, whereas the command-event hook and the init hook are.
```
use Consolidation\AnnotatedCommand\AnnotationData;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @hook interact some:command
 */
public function interact(InputInterface $input, OutputInterface $output, AnnotationData $annotationData)
{
    $io = new SymfonyStyle($input, $output);

    // If the user did not specify a password, then prompt for one.
    $password = $input->getOption('password');
    if (empty($password)) {
        $password = $io->askHidden("Enter a password:", function ($value) { return $value; });
        $input->setOption('password', $password);
    }
}
```

### Validate Hook

The purpose of the validate hook ([ValidatorInterface](src/Hooks/ValidatorInterface.php)) is to ensure the state of the targets of the current command are usabe in the context required by that command. Symfony has already validated the arguments and options prior to this hook.  It is possible to alter the values of the arguments and options if necessary, although this is better done in the configure hook. A validation hook may take one of several actions:

- Do nothing. This indicates that validation succeeded.
- Return a CommandError. Validation fails, and execution stops. The CommandError contains a status result code and a message, which is printed.
- Throw an exception. The exception is converted into a CommandError.
- Return false. Message is empty, and status is 1. Deprecated.

The validate hook may change the arguments and options of the command by modifying the Input object in the provided CommandData parameter.  Any number of validation hooks may run, but if any fails, then execution of the command stops.
```
use Consolidation\AnnotatedCommand\CommandData;

/**
 * @hook validate some:command
 */
public function validatePassword(CommandData $commandData)
{
    $input = $commandData->input();
    $password = $input->getOption('password');

    if (strpbrk($password, '!;$`') === false) {
        throw new \Exception("Your password MUST contain at least one of the characters ! ; ` or $, for no rational reason whatsoever.");
    }
}
```

### Command Hook

The command hook is provided for semantic purposes.  The pre-command and command hooks are equivalent to the post-validate hook, and should confirm to the interface ([ValidatorInterface](src/Hooks/ValidatorInterface.php)).  All of the post-validate hooks will be called before the first pre-command hook is called.  Similarly, the post-command hook is equivalent to the pre-process hook, and should implement the interface ([ProcessResultInterface](src/Hooks/ProcessResultInterface.php)).

The command callback itself (the method annotated @command) is called after the last command hook, and prior to the first post-command hook.
```
use Consolidation\AnnotatedCommand\CommandData;

/**
 * @hook pre-command some:command
 */
public function preCommand(CommandData $commandData)
{
    // Do something before some:command
}

/**
 * @hook post-command some:command
 */
public function postCommand($result, CommandData $commandData)
{
    // Do something after some:command
}
```

### Process Hook

The process hook ([ProcessResultInterface](src/Hooks/ProcessResultInterface.php)) is specifically designed to convert a series of processing instructions into a final result.  An example of this is implemented in Robo in the [CollectionProcessHook](https://github.com/consolidation/Robo/blob/master/src/Collection/CollectionProcessHook.php) class; if a Robo command returns a TaskInterface, then a Robo process hook will execute the task and return the result. This allows a pre-process hook to alter the task, e.g. by adding more operations to a task collection.

The process hook should not be used for other purposes.
```
use Consolidation\AnnotatedCommand\CommandData;

/**
 * @hook process some:command
 */
public function process($result, CommandData $commandData)
{
    if ($result instanceof MyInterimType) {
        $result = $this->convertInterimResult($result);
    }
}
```

### Alter Hook

An alter hook ([AlterResultInterface](src/Hooks/AlterResultInterface.php)) changes the result object. Alter hooks should only operate on result objects of a type they explicitly recognize. They may return an object of the same type, or they may convert the object to some other type.

If something goes wrong, and the alter hooks wishes to force the command to fail, then it may either return a CommandError object, or throw an exception.
```
use Consolidation\AnnotatedCommand\CommandData;

/**
 * Demonstrate an alter hook with an option
 *
 * @hook alter some:command
 * @option $alteration Alter the result of the command in some way.
 * @usage some:command --alteration
 */
public function alterSomeCommand($result, CommandData $commandData)
{
    if ($commandData->input()->getOption('alteration')) {
        $result[] = $this->getOneMoreRow();
    }

    return $result;
}
```

If an option needs to be provided with a default value, that may be done via the `@default` annotation.

```
use Consolidation\AnnotatedCommand\CommandData;

/**
 * Demonstrate an alter hook with an option that has a default value
 *
 * @hook alter some:command
 * @option $name Give the result a name.
 * @default $name George
 * @usage some:command --name=George
 */
public function nameSomeCommand($result, CommandData $commandData)
{
    $result['name'] = $commandData->input()->getOption('name')

    return $result;
}
```

### Status Hook

The status hook ([StatusDeterminerInterface](src/Hooks/StatusDeterminerInterface.php)) is responsible for determing whether a command succeeded (status code 0) or failed (status code > 0).  The result object returned by a command may be a compound object that contains multiple bits of information about the command result.  If the result object implements [ExitCodeInterface](ExitCodeInterface.php), then the `getExitCode()` method of the result object is called to determine what the status result code for the command should be. If ExitCodeInterface is not implemented, then all of the status hooks attached to this command are executed; the first one that successfully returns a result will stop further execution of status hooks, and the result it returned will be used as the status result code for this operation.

If no status hook returns any result, then success is presumed.

### Extract Hook

The extract hook ([ExtractOutputInterface](src/Hooks/ExtractOutputInterface.php)) is responsible for determining what the actual rendered output for the command should be.  The result object returned by a command may be a compound object that contains multiple bits of information about the command result.  If the result object implements [OutputDataInterface](OutputDataInterface.php), then the `getOutputData()` method of the result object is called to determine what information should be displayed to the user as a result of the command's execution. If OutputDataInterface is not implemented, then all of the extract hooks attached to this command are executed; the first one that successfully returns output data will stop further execution of extract hooks.

If no extract hook returns any data, then the result object itself is printed if it is a string; otherwise, no output is emitted (other than any produced by the command itself).

### On-Event hook

Commands can define their own custom events; to do so, they need only implement the CustomEventAwareInterface, and use the CustomEventAwareTrait. Event handlers for each custom event can then be defined using the on-event hook.

A handler using an on-event hook looks something like the following:
```
/**
 * @hook on-event custom-event
 */
public function handlerForCustomEvent(/* arbitrary parameters, as defined by custom-event */)
{
    // do the needful, return what custom-event expects
}
```
Then, to utilize this in a command:
```
class MyCommands implements CustomEventAwareInterface
{
    use CustomEventAwareTrait;

    /**
     * @command my-command
     */
    public myCommand($options = [])
    {
        $handlers = $this->getCustomEventHandlers('custom-event');
        // iterate and call $handlers
    }
}
```
It is up to the command that defines the custom event to declare what the expected parameters for the callback function should be, and what the return value is and how it should be used.

### Replace Command Hook

The replace-command ([ReplaceCommandHookInterface](src/Hooks/ReplaceCommandHookInterface.php)) hook permits you to replace a command's method with another method of your own.

For instance, if you'd like to replace the `foo:bar` command, you could utilize the following code:

```php
<?php
class MyReplaceCommandHook  {

  /**
   * @hook replace-command foo:bar
   * 
   * Parameters must match original command method. 
   */
  public function myFooBarReplacement($value) {
    print "Hello $value!";
  }
}
```

## Output

If a command method returns an integer, it is used as the command exit status code. If the command method returns a string, it is printed.

If the [Consolidation/OutputFormatters](https://github.com/consolidation/output-formatters) project is used, then users may specify a --format option to select the formatter to use to transform the output from whatever form the command provides to a string.  To make this work, the application must provide a formatter to the AnnotatedCommandFactory.  See [API Usage](#api-usage) below.

## Logging

The Annotated-Command project is completely agnostic to logging. If a command wishes to log progress, then the CommandFile class should implement LoggerAwareInterface, and the Commandline tool should inject a logger for its use via the LoggerAwareTrait `setLogger()` method.  Using [Robo](https://github.com/consolidation/robo) is recommended.

## Access to Symfony Objects

If you want to use annotations, but still want access to the Symfony Command, e.g. to get a reference to the helpers in order to call some legacy code, you may create an ordinary Symfony Command that extends \Consolidation\AnnotatedCommand\AnnotatedCommand, which is a \Symfony\Component\Console\Command\Command. Omit the configure method, and place your annotations on the `execute()` method.

It is also possible to add InputInterface and/or OutputInterface parameters to any annotated method of a command file (the parameters must go before command arguments).

## API Usage

If you would like to use Annotated Commands to build a commandline tool, it is recommended that you use [Robo as a framework](http://robo.li/framework), as it will set up all of the various command classes for you. If you would like to integrate Annotated Commands into some other framework, see the sections below.

### Set up Command Factory and Instantiate Commands

To use annotated commands in an application, pass an instance of your command class in to AnnotatedCommandFactory::createCommandsFromClass(). The result will be a list of Commands that may be added to your application.
```php
$myCommandClassInstance = new MyCommandClass();
$commandFactory = new AnnotatedCommandFactory();
$commandFactory->setIncludeAllPublicMethods(true);
$commandFactory->commandProcessor()->setFormatterManager(new FormatterManager());
$commandList = $commandFactory->createCommandsFromClass($myCommandClassInstance);
foreach ($commandList as $command) {
    $application->add($command);
}
```
You may have more than one command class, if you wish. If so, simply call AnnotatedCommandFactory::createCommandsFromClass() multiple times.

If you do not wish every public method in your classes to be added as commands, use `AnnotatedCommandFactory::setIncludeAllPublicMethods(false)`, and only methods annotated with @command will become commands.

Note that the `setFormatterManager()` operation is optional; omit this if not using [Consolidation/OutputFormatters](https://github.com/consolidation/output-formatters).

A CommandInfoAltererInterface can be added via AnnotatedCommandFactory::addCommandInfoAlterer(); it will be given the opportunity to adjust every CommandInfo object parsed from a command file prior to the creation of commands.

### Command File Discovery

A discovery class, CommandFileDiscovery, is also provided to help find command files on the filesystem. Usage is as follows:
```php
$discovery = new CommandFileDiscovery();
$myCommandFiles = $discovery->discover($path, '\Drupal');
foreach ($myCommandFiles as $myCommandClass) {
    $myCommandClassInstance = new $myCommandClass();
    // ... as above
}
```
For a discussion on command file naming conventions and search locations, see https://github.com/consolidation/annotated-command/issues/12.

If different namespaces are used at different command file paths, change the call to discover as follows:
```php
$myCommandFiles = $discovery->discover(['\Ns1' => $path1, '\Ns2' => $path2]);
```
As a shortcut for the above, the method `discoverNamespaced()` will take the last directory name of each path, and append it to the base namespace provided. This matches the conventions used by Drupal modules, for example.

### Configuring Output Formatts (e.g. to enable wordwrap)

The Output Formatters project supports automatic formatting of tabular output. In order for wordwrapping to work correctly, the terminal width must be passed in to the Output Formatters handlers via `FormatterOptions::setWidth()`.

In the Annotated Commands project, this is done via dependency injection. If a `PrepareFormatter` object is passed to `CommandProcessor::addPrepareFormatter()`, then it will be given an opportunity to set properties on the `FormatterOptions` when it is created.

A `PrepareTerminalWidthOption` class is provided to use the Symfony Application class to fetch the terminal width, and provide it to the FormatterOptions. It is injected as follows:
```php
$terminalWidthOption = new PrepareTerminalWidthOption();
$terminalWidthOption->setApplication($application);
$commandFactory->commandProcessor()->addPrepareFormatter($terminalWidthOption);
```
To provide greater control over the width used, create your own `PrepareTerminalWidthOption` subclass, and adjust the width as needed.

## Other Callbacks

In addition to the hooks provided by the hook manager, there are additional callbacks available to alter the way the annotated command library operates.

### Factory Listeners

Factory listeners are notified every time a command file instance is used to create annotated commands.
```
public function AnnotatedCommandFactory::addListener(CommandCreationListenerInterface $listener);
```
Listeners can be used to construct command file instances as they are provided to the command factory.

### Option Providers

An option provider is given an opportunity to add options to a command as it is being constructed.  
```
public function AnnotatedCommandFactory::addAutomaticOptionProvider(AutomaticOptionsProviderInterface $listener);
```
The complete CommandInfo record with all of the annotation data is available, so you can, for example, add an option `--foo` to every command whose method is annotated `@fooable`.

### CommandInfo Alterers

CommandInfo alterers can adjust information about a command immediately before it is created. Typically, these will be used to supply default values for annotations custom to the command, or take other actions based on the interfaces implemented by the commandfile instance.
```
public function alterCommandInfo(CommandInfo $commandInfo, $commandFileInstance);
```

## Comparison to Existing Solutions

The existing solutions used their own hand-rolled regex-based parsers to process the contents of the DocBlock comments. consolidation/annotated-command uses the [phpdocumentor/reflection-docblock](https://github.com/phpDocumentor/ReflectionDocBlock) project (which is itself a regex-based parser) to interpret DocBlock contents. 
