<?php

namespace App\Tests\feature;

use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class ProcessXMLFileTest extends DatabaseDependantTestCase {

    /** @test */
    public function test_file_processing_for_storage_csv_and_mode_local() {
        // SETUP
        $application = new Application(self::$kernel);

        // Command
        $command = $application->find('app-process-file');

        $commandTester = new CommandTester($command);
        $commandStatus = $commandTester->execute([
            '--storage' => 'csv',
            '--mode' => 'local'
        ]);
        $this->assertStringContainsString('[OK] Success! CSV file data.csv created successfully', $commandTester->getDisplay());
    }

    /** @test */
    public function test_file_processing_for_storage_csv_and_mode_remote() {
        // SETUP
        $application = new Application(self::$kernel);

        // Command
        $command = $application->find('app-process-file');

        $commandTester = new CommandTester($command);
        $commandStatus = $commandTester->execute([
            '--storage' => 'csv',
            '--mode' => 'remote'
        ]);
        $this->assertStringContainsString('[OK] Success! CSV file data.csv created successfully', $commandTester->getDisplay());
    }
    
    /** @test */
    public function test_file_processing_for_storage_database_and_mode_local() {
        // SETUP
        $application = new Application(self::$kernel);

        // Command
        $command = $application->find('app-process-file');

        $commandTester = new CommandTester($command);
        $commandStatus = $commandTester->execute([
            '--storage' => 'database',
            '--mode' => 'local'
        ]);
        $this->assertEquals(0, $commandStatus);
    }
    
    /** @test */
    public function test_file_processing_for_storage_database_and_mode_remote() {
        // SETUP
        $application = new Application(self::$kernel);

        // Command
        $command = $application->find('app-process-file');

        $commandTester = new CommandTester($command);
        $commandStatus = $commandTester->execute([
            '--storage' => 'database',
            '--mode' => 'remote'
        ]);
        $this->assertEquals(0, $commandStatus);
    }
}
