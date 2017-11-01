# Multimedia im Netz - Winter Semester 2017/2018 #
_Online Multimedia - Winter Term 2017/2018_


## Required Toolkit ##

To do the Break-Out exercises during the tutorials and to complete all assignments, make sure to install these tools *as soon as possible*. 

- Git. On Windows you need to install git from [https://git-scm.com/](https://git-scm.com/). It's already included on macOS and Linux. On Mac you might want to install the [XCode Command Line Tools](http://railsapps.github.io/xcode-command-line-tools.html) to make sure you get the latest version. 
    - After you're all set with git, go straight ahead to [this tutorial](https://rogerdudler.github.io/git-guide/), if you don't know git. 
    - Watch [this video](https://www.youtube.com/watch?v=Y9XZQO1n_7c) to get you all up and running with git.
    - We recommend generating an SSH key and cloning this repository via SSH.
    
- XAMP stack. We recommend [xampp](https://www.apachefriends.org/de/index.html) (for Windows and Linux) and [MAMP](https://www.mamp.info/) for macOS: 

    - using a bundle is the easiest way, but you can set up all components (Apache, PHP, MySQL) individually on your own peril.
    - create a file "test.php" and put this in it:
        ```php 
        <?php echo "Hello World!"; ?>
        ```
        Put the file in the appropriate location that XAMP uses to serve content.
        On Windows with xampp this is usually `C:/xampp/htdocs`, on macOS with MAMP its 
        If you see "Hello World!" when you go to  
    - You can install Apache and MySQL as "Service" to make sure they run in the background. We discourage doing this unless you have a good firewall. 
    
- NodeJS (+ npm). https://nodejs.org/en/.
    - MacOS: preferably via [Homebrew](https://brew.sh/) (or MacPorts if you already have that).  The package from the NodeJS website also works. 
    - Linux: the package in the repos are often a bit outdated, so please look for other ways to get the latest stable version
    - Windows: the version from the NodeJS website should work. 
- Once you have npm running (check via `npm -v`), install these packages (you can do all that from the Git-Bash):
  - Bower `npm install -g bower`
  - Express Generator `npm install -g express-generator`
  - Polymer-CLI `npm install -g polymer-cli`
  - Browser-Sync `npm install -g browser-sync`
  
- Text Editor / Web IDE *- choose one -* 
  - Atom https://atom.io/
  - Sublime https://www.sublimetext.com/
  - WebStorm (free for students here: https://www.jetbrains.com/shop/eform/students) -- warning: *no PHP support* 
  - VS Code [https://code.visualstudio.com/download](https://code.visualstudio.com/download) 

*Please come to the CodeLabs (Wednesdays 18:00 - 20:00) if you have trouble getting any of these tools up and running.*


## Repository Structure

### /assignments ###

Everything related to the assignments goes here.

#### skeletons
Contains code skeletons you can use to solve the tasks - these are optional and you don't have to use them, if you
prefer to create the code from scratch (sometimes this can be easier!).

#### solutions
**Commit your own solutions in the `solutions` sub-directories.** Read the [README](https://github.com/mimuc/mmn-ws1718/tree/master/assignments/solutions) first to find out how to do this. There won't be official solutions from our side. 

### /tutorials ###
all example code of the tutorials, break out material and other documents are here. 


