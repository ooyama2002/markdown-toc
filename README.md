# MarkdownToc
`MarkdownToc` creates a table of contents based on tags h1 to h6.  
It assigns ids to the tags h1 to h6 and allows you to jump to the tags from the links in the table of contents.

## How to use
```
<?php

require_once("MarkdownToc.php");

$html = <<<END
    <h1>heading 1</h1>
    <p>sentence1</p>
    <h2 id='testxxx'>heading 2</h2>
    <p>sentence2</p>
    <h3 id="testyyy">heading 3</h3>
    <p>sentence3</p>
END;

$toc = new MarkdownToc();

echo $toc->makeToc($html);

?>
```
- output image  
![output](https://github.com/user-attachments/assets/65cf34fc-584f-4a23-b318-357107412b26)

## License
- [MIT](https://opensource.org/license/MIT)
