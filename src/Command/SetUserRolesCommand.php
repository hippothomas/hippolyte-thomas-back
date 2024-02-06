<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:set-user-roles')]
class SetUserRolesCommand extends Command
{
    private const array ROLE_OPTIONS = [
        'admin' => 'ROLE_ADMIN',
        'user' => 'ROLE_USER',
    ];

    public function __construct(
        private readonly UserRepository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'User Roles Manager',
            '============',
        ]);

        // Find the user
        $username = $input->getArgument('username');
        $user = $this->repository->findOneBy([
            'username' => $username,
        ]);
        if (null === $user) {
            $output->writeln(sprintf("User '%s' not found.", $username));

            return Command::FAILURE;
        }

        // Check if role is a valid option
        $role = $input->getArgument('role');
        if (!array_key_exists($role, self::ROLE_OPTIONS)) {
            $output->writeln(sprintf("Invalid option '%s' for argument role.", $role));

            return Command::INVALID;
        }

        $roles = $user->getRoles();
        $arg_role = self::ROLE_OPTIONS[$role];
        // Check if the user have the role
        if (in_array($arg_role, $roles)) {
            unset($roles[array_search($arg_role, $roles)]);
            $output->writeln(sprintf("Role '%s' has been removed from user '%s'.", $role, $username));
        } else {
            $roles[] = $arg_role;
            $output->writeln(sprintf("Role '%s' has been added to user '%s'.", $role, $username));
        }

        // Updating the roles
        $user->setRoles($roles);
        $this->repository->save($user, true);

        $output->writeln('============');
        $output->writeln('User : '.$username);
        $output->writeln('Roles :');
        $output->writeln($roles);
        $output->writeln('============');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Set the user roles.')
            ->setHelp('This command allows you to set the roles for a user.')
            ->addArgument('role', InputArgument::REQUIRED, 'User role')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
        ;
    }
}
