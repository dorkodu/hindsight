<?php
  namespace Hindsight;

  use Hindsight\Settler\StateLocker;
  use Hindsight\Settler\SampleProject;
  use Hindsight\Settler\SettingsResolver;
  use Hindsight\Utils\CLITinkerer;
  use Hindsight\Utils\TerminalUI;

  class Hindsight
  {
    /**
     * WebsiteProject for Hindsight
     */
    protected $website;
    
    /**
     * Class constructor.
     */
    public function __construct(string $projectDirectory)
    {
      $this->website = new WebsiteProject(realpath($projectDirectory));
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
  Hindsight is created to make publishing a static website easier in a simple & fast way.

  You only give your website data in a JSON file.
  Create your pages as Markdown files.
  Hindsight composes a static website from the Markdown files you created. No magic, very simple :D
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
      TerminalUI::dictionaryEntry("pages/ folder", "You will give your web pages as Markdown files here.");
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
       * if not already initted by Hindsight, then :
       * -> CREATE a sample project here:
       * -> generate that template's hash and lock the state into hindsight.lock
       */
      if ($this->website->isProject()) {
        self::notice("Already has a project in this folder.");
      } else {

        if ($this->website->isInitted()) {
          self::notice("Already initted this folder.");
        }

        self::consoleLog("Hindsight will create a sample project here.");

        # create a sample project in that folder
        SampleProject::create($this->website->getDirectory());
        $this->lockState();
        
        self::consoleLog("Done.");
        self::consoleLog("Don't forget to read 'README.txt', we've a surprise for you :)");
      }
    }

    /**
     * Locks the project state if something is untracked
     *
     * @return void
     */
    private function lockIfStateIsUntracked()
    {
      if ($this->isStateLocked() === false) {
        $this->lockState();
      }
    }

    /**
     * Locks the current state of the app
     *
     * @return void
     */
    private function lockState()
    {
      # the serialized state of the project
      $state = $this->website->getState();

      echo "\n\n " . $state . "\n\n";
      # lock the state, return the result
      if(StateLocker::lock( $state, $this->website->getDirectory()) === false) {
        self::problem("Couldn't lock the state.");
      }
    }

    /**
     * Tells if the current state is locked
     *
     * @return boolean
     */
    private function isStateLocked()
    {
      if ($this->website->isInitted()) {
          # Folder is initted, check for the project state
          # Get state, then give it to StateLocker
          $currentState = $this->website->getState();
          # return if the state locked for a given website project
          return StateLocker::isStateLocked($this->website->getDirectory(), $currentState);

      } else self::problem("Folder is not initted. Please run 'init' before to create a new Hindsight project.");
    }

    /**
     * Composes the contents and generates a fresh new website.
     */
    public function compose()
    {
      if ($this->website->isInitted()) {
        self::consoleLog("Current folder is initted. Hindsight is running.");

        # if is a project, "compose" it
        if ($this->website->isProject()) {
          # COMPOSE
          
          # after you compose it, lock the state
          $this->lockIfStateIsUntracked();

        } else self::problem("This is not a complete project. Please create your contents, or use 'init' to create a sample project.");
      } else self::problem("Folder is not initted. Please run 'init' before to create a new Hindsight project.");
    }

    public static function consoleLog($text)
    {
      TerminalUI::bold("Hindsight > ");
      CLITinkerer::write($text);
      CLITinkerer::breakLine();
    }

    private static function breakRunning($topic, $content)
    {
      TerminalUI::bold("Hindsight > " . $topic);
      CLITinkerer::write(" : ".$content);
      CLITinkerer::breakLine();
      exit;
    }

    public static function notice($message)
    {
      self::breakRunning("NOTICE", $message);
    }
    
    public static function problem($message)
    {
      self::breakRunning("PROBLEM", $message);
    }
  }