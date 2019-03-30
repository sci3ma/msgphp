<?php

declare(strict_types=1);

namespace MsgPhp\User\Infrastructure\Console\Command;

use MsgPhp\User\Command\DeleteUser;
use MsgPhp\User\Event\UserDeleted;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
final class DeleteUserCommand extends UserCommand
{
    protected static $defaultName = 'user:delete';

    /**
     * @var StyleInterface
     */
    private $io;

    public function onMessageReceived($message): void
    {
        if ($message instanceof UserDeleted) {
            $this->io->success('Deleted user '.$message->user->getCredential()->getUsername());
        }
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Delete a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $user = $this->getUser($input, $this->io);
        $userId = $user->getId();

        if ($input->isInteractive()) {
            $this->io->note('Deleting user '.$user->getCredential()->getUsername().' with ID '.$userId->toString());

            if (!$this->io->confirm('Are you sure?')) {
                return 0;
            }
        }

        $this->dispatch(DeleteUser::class, compact('userId'));

        return 0;
    }
}
