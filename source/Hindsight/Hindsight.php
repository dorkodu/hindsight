<?php
  namespace Hindsight;

  use Hindsight\Settler\StateLocker;
  use Hindsight\Settler\SettingsResolver;
  use Hindsight\FileStorage;
  use Hindsight\Utils\CLITinkerer;
  use Hindsight\Utils\TerminalUI;
  use Hindsight\Json\JsonFile;
  use Hindsight\Json\JsonPreprocessor;

  class Hindsight {
    protected $projectDirectory;
    
    /**
     * Class constructor.
     */
    public function __construct(string $projectDirectory)
    {
      $this->projectDirectory = realpath($projectDirectory);

      set_exception_handler(function($exception) {
        Hindsight::problem($exception->getMessage());
      });
    }
    
    /**
     * Hindsight's igniter :D
     */
    public function run() {
      /**
       * checks for updates
       * checks if config is OK
       * checks for hindsight.json, hindsight.lock files
       */
      switch (CLITinkerer::getArgument(1)) {
        case 'about':
          $this->about();
          break;
        case 'help':
          $this->help();
          break;
        case 'init':
          $this->init();
          break;
        case 'compose':
          $this->compose();
          break;
        case 'status':
          $this->status();
          break;
        default:
          $this->greet();
          break;
      }
    }

    /**
     * Simply greets users.
     */
    public function greet()
    {
      CLITinkerer::breakLine();
      TerminalUI::dotTitle("Hindsight");
      CLITinkerer::breakLine();
      $greetString = 
 "  Hindsight is a Markdown based static website generator.
  Hindsight makes creating, deploying and maintaining static websites easier.
  See 'https://libre.dorkodu.com/hindsight' for further knowledge and documentation.
  
  Usage :
  > php hindsight <command>
  
  Proudly brought you by Dorkodu.
  See how we change the future with Dorkodu @ 'https://dorkodu.com'

  Type 'help' to get a list of commands for Hindsight.
      ";

      CLITinkerer::writeLine($greetString);
    }
    
     /**
     * Writes some basic info about Hindsight
     */
    public function about()
    {
      $this->greet();

      $aboutString = "
  Hindsight is created to make easier publishing a static website in a simple & fast way.

  You only declare your website project's information in a JSON file.
  Hindsight composes a static website from the markdown files you created. No magic, just simplicity :D
  It's simply a Markdown-to-HTML composer with adding placeholders feature for templating.
      ";

      CLITinkerer::writeLine($aboutString);
      TerminalUI::underDashedTitle("Creator");
      TerminalUI::titledParagraph("Doruk Dorkodu", "Software Engineer, Founder & Chief @ Dorkodu".PHP_EOL."  See more 'https://dorkodu.com/doruk".PHP_EOL."  Email : doruk@dorkodu.com".PHP_EOL);
    }

    /**
     * Prints the help page (a simple monolith documentation text) for Hindsight.
     */
    public function help()
    {
      $this->greet();
      CLITinkerer::breakLine();
      CLITinkerer::writeLine("  If you are just curious about Hindsight, type 'about' command to know more.");
      CLITinkerer::breakLine();
      
      # how to use Hindsight ?
      TerminalUI::underDashedTitle("How to use Hindsight?");
      CLITinkerer::writeLine("  Copy Hindsight's binary to the folder you want to work in.");
      CLITinkerer::writeLine("  Launch Hindsight from Terminal to be in that folder");
      CLITinkerer::writeLine("  For the first time in that folder, use 'init' command.");
      CLITinkerer::writeLine("  It will set the environment, and create a sample website project.");
      CLITinkerer::writeLine("  Then you give your website data with JSON format, in 'hindsight.json' file.");
      CLITinkerer::writeLine("  You can create pages using Markdown, in 'pages/' folder.");
      CLITinkerer::writeLine("  When you're done, use 'compose' command.");
      CLITinkerer::writeLine("  Hindsight will 'compose' your content and generate a static website in 'composed/' folder.");
      CLITinkerer::writeLine("  That's it! Your site is ready to deploy.");
      TerminalUI::definition("  Note", "Don't forget to run 'compose' after each time you manipulate the contents.");
      CLITinkerer::breakLine();

      # useable commands list
      TerminalUI::underDashedTitle("Possible Actions");
      CLITinkerer::writeLine("  List of available commands :");
      CLITinkerer::breakLine();
      TerminalUI::dictionaryEntry("init", "Hindsight will prepare the project folder for its operations. Create an empty project template.");
      TerminalUI::dictionaryEntry("about", "You can learn more about Hindsight. It's recommended to read :)");
      TerminalUI::dictionaryEntry("compose", "Hindsight will 'compose' your contents and generate a static website in 'composed/' folder.");
      TerminalUI::dictionaryEntry("status", "Tells you if something is changed and website should be composed again.");
      TerminalUI::dictionaryEntry("help", "The simple documentation on Hindsight, which is exteremely useful. You are reading it now :)");
      CLITinkerer::breakLine();

      # stuff related to Hindsight
      TerminalUI::underDashedTitle("What Hindsight does in my folder?");
      TerminalUI::dictionaryEntry("hindsight", "This is the Hindsight CLI util. Run it from the terminal, in the project folder.");
      TerminalUI::dictionaryEntry("hindsight.json", "The file you give your website data.");
      TerminalUI::dictionaryEntry("hindsight.lock", "Hindsight will save its last run state in this file. For tracking changes.");
      TerminalUI::dictionaryEntry("pages/ folder", "Contents of your site will be created there, after running 'compose'.");
      TerminalUI::dictionaryEntry("assets/ folder", "You can put your assets, and then give directives in 'hindsight.json'");
      TerminalUI::dictionaryEntry("composed/ folder", "Hindsight will put your composed website into this folder.");
      CLITinkerer::breakLine();
    }

    /**
     * Outputs the status of website project
     */
    public function status()
    {
      if ($this->isStateLocked()) {
        self::consoleLog("There are no changes. Website is up-to-date !");
      } else self::notice("There are untracked changes. Use 'compose' and generate a fresh website !");
    }

    /**
     * Initialises the environment for Hindsight, in given directory.
     */
    public function init()
    {
      /**
       * if not already used by Hindsight, then :
       * -> CREATE :
       *    - an empty hindsight.json
       *    - an empty README.txt file
       *    - composed/ folder for storing composed website
       *    - assets/ folder for storing static asset files
       *    - pages/ folder for storing MD files
       *    - generate that template's hash and lock the state into hindsight.lock
       */
       if (FileStorage::isUsefulDirectory($this->projectDirectory)) {
        if ($this->isInittedDirectory($this->projectDirectory)) {
          self::consoleLog("Already initted folder.");
          return true;
        } else {
          # create a SampleProject
        }
      } else self::problem("Current folder is not useful. Check read/write permissions.");
    }

    private function lockState(JsonFile $HindsightJson)
    {
      return StateLocker::lock($HindsightJson);
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    public function isInittedDirectory()
    {
      return (FileStorage::isUsefulFile($this->projectDirectory."/hindsight.json") && FileStorage::isUsefulFile($this->projectDirectory."/hindsight.lock"));
    }

    /**
     * Tells if the current state is locked
     *
     * @return boolean
     */
    private function isStateLocked()
    {
      if (FileStorage::isUsefulDirectory($this->projectDirectory)) {
        # Folder is useful. Hindsight is running.
        if ($this->isInittedDirectory()) {
          # Current folder is OK. Looking for project
          $HindsightJson = new JsonFile($this->projectDirectory."/hindsight.json");
          
          if ($HindsightJson->isUseful()) {
            # check if current state locked
            $isStateLocked = StateLocker::isCurrentStateLocked($HindsightJson);
            # check if all files are tracked
            $areAllFilesAreTracked = true; # change this
            
            return ($isStateLocked && $areAllFilesAreTracked);
            # } else self::notice("There are untracked changes. Use 'compose' and generate from fresh contents.");

          } else self::problem("'hindsight.json' is not useful. Check read/write permissions or if it exists.");
        } else self::problem("Folder is not initted. Please run 'init' before to create a new Hindsight project.");
      } else self::problem("Folder is not useful. Check for read/write permissions.");
    }

    /**
     * Weaves the dependencies in given folder.
     */
    public function compose()
    {
      self::consoleLog("Compose!");

      /**
       * read hindsight.json and hindsight.lock, compare states :
       * 
       * - if state is changed
       *    -> create loot/ and fill it
       *    -> generate a fresh autoloading script, and lock the state.
       * - else
       *    -> dont touch it :P
       */
      /*
      if (FileStorage::isUsefulDirectory($this->projectDirectory)) {
        self::consoleLog("Folder is useful. Hindsight is running.");
        
        if ($this->isInittedDirectory()) {
          
          self::consoleLog("Current folder is OK. Looking for project.");
          $HindsightJson = new JsonFile($this->projectDirectory."/hindsight.json");
          
          if ($HindsightJson->isUseful()) {
            
            $isStateLocked = DependencyLocker::isCurrentStateLocked($HindsightJson);
            if ($isStateLocked) {
              $this->breakRunning("NOTICE", "Already composed the contents. Current state is locked.");
            } else {
              
              $rootDependenciesArray = DependencyResolver::resolve($HindsightJson);
              
              if(is_array($rootDependenciesArray) && !empty($rootDependenciesArray)) {
                
                $this->consoleLog("Resolved dependencies.");

                $isLootReady = $this->createLoot();
                if ($isLootReady) {
                  $this->consoleLog("Initted Loot.");

                  $weaveResult = $this->saveHindsightWeaver($rootDependenciesArray);
                  if ($weaveResult) {
                    $this->consoleLog("Generated the new weaver script.");
                    
                    $lockResult = DependencyLocker::lock($HindsightJson);
                    if ($lockResult) {
                      $this->consoleLog("Locked the current state.");
                      $this->consoleLog("Successfully weaved your dependencies.");
                      $this->consoleLog("Done.");
                    } else $this->breakRunning("PROBLEM", "Couldn't lock the state.");

                  } else $this->breakRunning("PROBLEM", "Couldn't generate or save the new weaver script.");
               
                } else $this->breakRunning("PROBLEM", "Coulnd't create or init Loot (loot/ directory). Check your read/write permissions.");
              } else $this->breakRunning("PROBLEM", "Couldn't resolve dependencies. Reason may be your hindsight.json file."); 
            }
          } else $this->breakRunning("PROBLEM", "'Hindsight.json' is not useful. Check read/write permissions or if it exists.");
        } else $this->breakRunning("PROBLEM", "Current directory is not initted. Please run 'init' before.");  
      } else self::breakRunning("PROBLEM", "Current directory is not useful. Check for read/write permissions.");
      */
    }

    private static function consoleLog($text)
    {
      TerminalUI::bold("Hindsight");
      CLITinkerer::write(" > ". $text);
      CLITinkerer::breakLine();
    }

    private static function breakRunning($topic, $content)
    {
      TerminalUI::bold("Hindsight > ");
      TerminalUI::bold($topic);
      CLITinkerer::write(": ".$content);
      CLITinkerer::breakLine();
      exit;
    }

    private static function notice($message)
    {
      self::breakRunning("NOTICE", $message);
    }
    
    private static function problem($message)
    {
      self::breakRunning("PROBLEM", $message);
    }
  }