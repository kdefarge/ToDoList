# How contributed to ToDoList

This documentation will explain how to make changes to the project

## Table of Contents

-   [Check existing Issues and Pull Requests](#Check-existing-Issues-and-Pull-Requests)
-   [Setup your Environment](#Setup-your-Environment)
    -   [Install the Software Stack](#Install-the-Software-Stack)
    -   [Configure Git](#Configure-Git)
    -   [Get the ToDoList Source Code](#Get-the-ToDoList-Source-Code)
    -   [Check that the current Tests Pass](#Check-that-the-current-Tests-Pass)
-   [Work on your Pull Request](#Work-on-your-Pull-Request)
    -   [Create a Topic Branch](#Create-a-Topic-Branch)
    -   [Work on your Pull Request](#Work-on-your-Pull-Request)
-   [Submit your Pull Request](#Submit-your-Pull-Request)
    -   [Rebase your Pull Request](#Rebase-your-Pull-Request)
    -   [Make a Pull Request](#Make-a-Pull-Request)
    -   [Rework-your-Pull-Request](#Make-a-Pull-Request)
-   [License](#License)

## Check existing Issues and Pull Requests

Before working on a change, check to see if someone else also raised the topic or maybe even started working on a PR by searching on GitHub.

If you can't find an answer, start your change by opening an issue.

## Setup your Environment

### Install the Software Stack

Before working on Symfony, setup a friendly environment with the following software:

-   Git
-   PHP version 7.2.5 or above

### Configure Git

```bash
git config --global user.name "Your Name"
git config --global user.email you@example.com
```

### Get the ToDoList Source Code

Get the Symfony source code:

-   Create a GitHub account and sign in
-   Fork the ToDoList repository (click on the “Fork” button)
-   After the “forking action” has completed, clone your fork locally (this will create a ToDoList directory)

```bash
git clone git@github.com:USERNAME/ToDoList.git
```

-   Add the upstream repository as a remote:

```bash
cd ToDoList
git remote add upstream git@github.com:kdefarge/ToDoList.git
composer install
```

### Check that the current Tests Pass

Run tests locally before submitting a patch for inclusion, to check that you have not broken anything.

-   Running the Tests

```bash
php bin/phpunit
```

## Work on your Pull Request

### Create a Topic Branch

Each time you want to work on a PR for a bug or on an enhancement, create a topic branch:

```bash
git checkout -b topic-TOPIC_NAME --track origin/master
```

### Work on your Pull Request

Work on the code as much as you want and commit as much as you want; but keep in mind the following:

-   follow the coding [standards](https://symfony.com/doc/current/contributing/code/standards.html)
-   Add unit tests to prove that the bug is fixed or that the new feature actually works
-   Try hard to not break backward compatibility (if you must do so, try to provide a compatibility layer to support the old way)
-   Do atomic and logically separate commits (use the power of git rebase to have a clean and logical history);
-   Write good commit messages: Start by a short subject line (the first line), followed by a blank line and a more detailed description.

## Submit your Pull Request

Whenever you feel that your PR is ready for submission, follow the following steps.

### Rebase your Pull Request

Before submitting your PR, update your branch (needed if it takes you a while to finish your changes)

```bash
git checkout master
git fetch upstream
git merge upstream/master
git checkout topic-TOPIC_NAME
git rebase master
```

When doing the rebase command, you might have to fix merge conflicts. git status will show you the unmerged files. Resolve all the conflicts, then continue the rebase:

```bash
git add ... # add resolved files
git rebase --continue
```

Check that all tests still pass and push your branch remotely:

```bash
git push --force origin topic-TOPIC_NAME
```

### Make a Pull Request

You can now make a pull request on the kdefarge/ToDoList GitHub repository.

In the pull request description, give as much detail as possible about your changes (don’t hesitate to give code examples to illustrate your points). If your pull request is about adding a new feature or modifying an existing one, explain the rationale for the changes. The pull request description helps the code review and it serves as a reference when the code is merged (the pull request description and all its associated comments are part of the merge commit message).

### Rework your Pull Request

Based on the feedback on the pull request, you might need to rework your PR. Before re-submitting the PR, rebase with upstream/master, don’t merge; and force the push to the origin:

```bash
git rebase -f upstream/master
git push --force origin topic-TOPIC_NAME
```

## License

[MIT](https://github.com/kdefarge/BileMoAPI/blob/master/LICENSE.md) © Kévin DEFARGE inspired by [Symfony documentation](https://symfony.com/doc/current/contributing/code/pull_requests.html#create-a-topic-branch)
