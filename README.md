# Sinngrund kulturdatenbank plugin

## Github repository setting for Sinngrund

- @ github
  
  - account
  
  - repository url: https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin
  
  - repository name: Sinngrund-Kulturdatenbank-plugin

- @ Local computer
  
  - wpLocal - make a local website under plugin directory
    
    `git clone https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin.git`
  
  - ```
    git config --global user.name "diane-at-Okto"
    git config --global user.email "diane.kang@page-effect.com"
    ```
  
  - To check user name
    
    `git config --list`
  
  - `~/.../Sinngrund-Kulturdatenbank-plugin$ git init`

## Register Custom  Block/ datenbankblock

- Git commit 
  
  "Register kulturedatenbank Block", 2022-08-05)

## ~~Modity Custom Block, define block template~~

- Set default block(let my custom block show up always first)

- ~~Modify the Custom Block with InnerBlock~~~ <mark>Error appears</mark>
  
  Need to check, If i am dealing with JSX oder JS 
  
  What happend her: Using JS code -> Expected to work under JSX enviroment
  
  ```javascript
       return el( InnerBlocks, {
         template: BLOCKS_TEMPLATE,
         templateLock: false,
     } );
  ```



### JSX for my custom Plugin

- npm init -y 
  
  generate file package.json : For tracking the changes of node module

- Install WP script 
  
  ``$ npm install @wordpress/scripts --save-dev``

- Prepare for JSX
  
  make a directory named : **src**
  
  under src directory make a file named: **index.js**
  
  copy all contents from test.js to index.js 
  
  <mark>.js file under src is for JSX.   js.file outside of src directory is for Javascript </mark>
  
  as well the enqueue script loacation update
