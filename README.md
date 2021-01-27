![Hindsight Logo](hindsight.png)

# Hindsight

## What?

Hindsight is a Markdown based static website generator. <br>Hindsight makes creating, deploying and maintaining static websites easier.

## Why?

Because I wanted to have an automated way for creating static websites.<br>It became hard to develop and maintain documentation websites. Using a CMS was just overkill.<br>So I asked myself, what changes on templates among websites? **Settings and Content.**<br>Everything is automated. Give what is needed, run Hindsight and get your website content!

### What we need for generating a static website :

- a configuration file for website information (**JSON**)
- contents of pages (**Markdown**)
- a single template file (**HTML**) -- you can also have CSS and JS

Give these to Hindsight and get what you want as a static website!

## How?

Simple. Here is a formula, if we were to think Hindsight as a math function :
$$
Hindsight(JSON+HTML+Markdown) = website
$$
Create a folder for your website project.<br>

> ### NOTE
>
> Hindsight is a PHP app. It is actually a PHAR binary.<br>So you have to use **PHP CLI** to run Hindsight.

Run Hindsight in that folder from terminal.

```bash
> php hindsight <command>
```

## Commands

- ### init

  Creates an empty website project in the current folder.<br>**This is a sample project directory structure :**

  ```bash
  [ROOT]
  |-- README.md
  |-- hindsight.json
  |-- hindsight.lock
  |-- composed/
  |-- pages/
  		|-- index.md
  		|-- <page-name>.md
  		···
  |-- static/
  		|-- <your-style>.css
  		···
  		|-- <your-script>.js
  		···
  ```

- ### compose

  Composes your website and publishes into ***composed/*** folder.<br>You can directly upload what is inside ***composed/*** folder, to your server.<br>If you wish, you can also put some more stuff in. Like images, assets. Anything.<br>It's freedom !

- ### status

  Tells you if something is changed and website should be composed again.<br>You can see your website project's current status with this command.

- ### about

  Gives information about Hindsight.

- ### help

  Shows a simple documentation on Hindsight.

## Guide

So, what to do? Well, simple.<br>Hindsight will generate a page for each of your markdown files.

- ### hindsight.json

  Your website configuration file. It is a monolith settings file.<br>You define some properties here, about your website.

  - #### placeholders

    You give your **[placeholder] + [contents]** as **"key": "value"** pairs.<br>These placeholders will be replaced by their contents in each of your pages.  
    
  - #### assets 

    This is an array of static files that you want to copy from **static/** folder to a path in **composed/** folder<br>You must give a **file name** and a path in **composed/** folder. 
    
    #### For example :
    
    You have `static/style.css` <br>You want to place this file into **assets/** folder in **composed/** folder.<br>Then, the field must be like this : `"style.css": "assets/style.css"`.
    
    This is actually a "copy to" directive for Hindsight. You give a file name and a new path.
    
    > #### NOTE
    >
    > The new path for your static file **_MUST_** be relative to **composed/** folder.<br>Hindsight will assume that your "copy to" path will be _IN_ **composed/** folder.<br>These are rules. Sorry :(
    
    

- ### hindsight.lock

  Hindsight locks the current state of the website, into this file.<br>This is used for understanding if something is changed.

- ### page.html

  Your single HTML template file.<br>You put placeholders in this file. This is the pattern for placeholders : `{{ placeholder }}`<br>It can have any CSS or JS. Hindsight **DOES NOT** handle your assets.<br>You can put them into your **composed/** folder as how you wish.



## Author

Doruk Dorkodu : [GitHub](https://github.com/dorukdorkodu)  | [Twitter](https://twitter.com/dorukdorkodu) | [doruk@dorkodu.com](mailto:doruk@dorkodu.com) | [dorkodu.com](https://dorkodu.com)

See also the list of [contributions](https://libre.dorkodu.com) that we are making at [Dorkodu](dorkodu.com) to the free software community.

## License

Hindsight is open-sourced software licensed under the [MIT license](LICENSE).

