# Sinngrund kulturdatenbank plugin

## Git Weiterbildung

    [Web-Dev-For-Beginners/1-getting-started-lessons/2-github-basics at main · microsoft/Web-Dev-For-Beginners · GitHub](https://github.com/microsoft/Web-Dev-For-Beginners/tree/main/1-getting-started-lessons/2-github-basics)

## Github repository setting & local git setting

- @ github
  
  - account
  
  - repository url: https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin
  
  - repository name: Sinngrund-Kulturdatenbank-plugin

- @ Local computer 
  
  - wpLocal - make a local website under plugin directory 
    
    ``git clone https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin.git``
  
  - ```
    git config --global user.name "diane-at-Okto"
    git config --global user.email "diane.kang@page-effect.com"
    ```
  
  - To check user name 
    
    ``git config --list``
  
  - ``~/.../Sinngrund-Kulturdatenbank-plugin$ git init``

## Register Custom  Block/ datenbankblock

- Git commit 
  
  "Register kulturedatenbank Block", 2022-08-05)

## Modity Custom Block, define block template

- Set default block(let my custom block show up always first)

- Modify the Custom Block with InnerBlock

- Git commit
  
  c6c7f91 ("Define block templates for new post & InnerBlocks setting for my custom block", 2022-08-05)

### JSX for my custom Plugin

- npm init -y 
  
  generate file package.json : For tracking the changes of node module

- Install WP script 
  
  ``$ npm install @wordpress/scripts --save-dev``

- Prepare for JSX
  
  make a directory named : **src**
  
  under src directory make a file named: **index.js**
  
  copy all contents from test.js to index.js
  
  as well the enqueue script loacation update

## Terminal setting, showing the present brach name on the terminal

### Ubuntu: Show your branch name on your terminal

Add these lines in your ~/.bashrc file

```bash
# Show git branch name
force_color_prompt=yes
color_prompt=yes
parse_git_branch() {
 git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/(\1)/'
}
if [ "$color_prompt" = yes ]; then
 PS1='${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u@\h\[\033[00m\]:\[\033[01;34m\]\w\[\033[01;31m\]$(parse_git_branch)\[\033[00m\]\$ '
else
 PS1='${debian_chroot:+($debian_chroot)}\u@\h:\w$(parse_git_branch)\$ '
fi
unset color_prompt force_color_prompt
```

Reload the .bashrc file with this command:

```bash
$ source ~/.bashrc
```
