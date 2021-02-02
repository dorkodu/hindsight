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
  |-- README.txt
  |-- hindsight.json
  |-- hindsight.lock
  |-- composed/
  		|-- index.html
  		|-- <page-name>.html
  |-- pages/
  		|-- index.md
  		|-- <page-name>.md
  		···
  ```
  
- ### compose

  Composes your website and publishes into ***composed/*** folder.<br>You can directly upload what is inside ***composed/*** folder, to your server.<br>If you wish, you can also put some more stuff in. Like images, CSS, JS. Anything.<br>It's freedom !

- ### status

  Tells you if something is changed and website should be composed again.<br>You can see your website project's current status with this command.

- ### about

  Gives information about Hindsight.

- ### help

  Shows a simple documentation on Hindsight.

## The Beginners Guide to Hindsight

So, what to do? Well, simple.<br>

- ### hindsight.json

  Your website data file. It is like a monolith settings file.<br>For now, you define placeholders here, which are in your HTML template file.

  - #### data

    You give your **[placeholder] + [contents]** as **"key": "value"** pairs.<br>These placeholders will be replaced by their contents in each of your pages.  
    
    > #### Important !
    >
    > There is a reserved placeholder, which is **{{ $markdown }}**.<br>This points to where your Markdown contents will be injected into.<br>So **DO NOT FORGET** to put this placeholder in your template !
    
    This is a sample **hindsight.json** :
    
    ```json
    {
      "data": {
        "title": "Hello!",
        "contents": "This is some text.",
        "author": "Doruk Dorkodu"
      }
    }
    ```
    
    
  
- ### hindsight.lock

  Hindsight locks the current state of the website, into this file.<br>This is used for tracking changes in your contents.

- ### page.html

  Your single HTML template file.<br>You put placeholders in this file. This is the pattern for placeholders : `{{ placeholder }}`<br>If you set "placeholder" to a string in hindsight.json, Hindsight will replace the placeholder with its value.
  
  > Your template file can have any CSS or JS. Hindsight **DOES NOT (!)** handle your assets.<br>You can put them into your **composed/** folder as how you wish.<br>
  
- ### pages/

  This is the folder you put your pages as Markdown files.<br>For each Markdown file in this folder, Hindsight will create a data-seeded HTML file with its name.<br>For example you have these two **".md "** files in **pages/** folder :

  `index.md` + `about.md` 	>>	 `index.html` + `about.html`

  You will get two **".html"** files in **composed/** folder.<br>Which are seeded with data you give in **hindsight.json** file. 

## Author

Doruk Dorkodu : [GitHub](https://github.com/dorukdorkodu)  | [Twitter](https://twitter.com/dorukdorkodu) | [doruk@dorkodu.com](mailto:doruk@dorkodu.com) | [dorkodu.com](https://dorkodu.com)

See also the list of [contributions](https://libre.dorkodu.com) that we are making at [Dorkodu](dorkodu.com) to the free software community.

## License

Hindsight is open-sourced software licensed under the [MIT license](LICENSE).
