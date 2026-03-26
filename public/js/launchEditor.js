/*import { ClassicEditor, Essentials, Bold, Italic, Font, Paragraph } from './ckeditor5/ckeditor5.js';*/
import {
    ClassicEditor,
    Autoformat,
    Bold,
    Italic,
    Underline,
    BlockQuote,
    Essentials,
    Heading,
    Indent,
    IndentBlock,
    Link,
    List,
    MediaEmbed,
    Mention,
    Paragraph,
    Table,
    TableColumnResize,
    TableToolbar,
    TextTransformation
} from './ckeditor5/ckeditor5.js';

let editor = null;

loaded(function () {
    const postData = '<p>Edit here !</p>';
    /* chargement de l'éditeur classique */
    /* chargement de l'éditeur classique */
    ClassicEditor
    .create(document.querySelector('#editor'), {
        licenseKey: 'GPL', // Or <YOUR_LICENSE_KEY>
        /*plugins: [ Essentials, Paragraph, Bold, Italic, Font ],*/
        plugins: [
            Autoformat,
            BlockQuote,
            Bold,
            Essentials,
            Heading,
            Image,
            Indent,
            IndentBlock,
            Italic,
            Link,
            List,
            MediaEmbed,
            Mention,
            Paragraph,
            Table,
            TableColumnResize,
            TableToolbar,
            TextTransformation,
            Underline,
        ],
        /*
        toolbar: [
            'undo', 'redo', '|', 'bold', 'italic', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
        ],
        */
        toolbar: [
            'undo',
            'redo',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            '|',
            'link',
            'insertTable',
            'blockQuote',
            'mediaEmbed',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent'
        ],
        heading: {
            options: [
                {
                    model: 'paragraph',
                    title: 'Paragraph',
                    class: 'ck-heading_paragraph'
                },
                {
                    model: 'heading2',
                    view: 'h2',
                    title: 'Heading 2',
                    class: 'ck-heading_heading2'
                },
                {
                    model: 'heading3',
                    view: 'h3',
                    title: 'Heading 3',
                    class: 'ck-heading_heading3'
                },
                {
                    model: 'heading4',
                    view: 'h4',
                    title: 'Heading 4',
                    class: 'ck-heading_heading4'
                }
            ]
        },
        link: {
            addTargetToExternalLinks: true,
            defaultProtocol: 'https://'
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        },
        licenseKey: 'GPL',
        initialData: postData,
    })
    .then(neweditor => {
        window.editor = neweditor;
        editor = neweditor;
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
        return false;
    });
    
    q('#valid').addEventListener('click', function(event){
        const editorData = editor.getData();
        q('#ckcontent').value = editorData;
        event.preventDefault();
        q('#form-article').submit();
    }); 
});