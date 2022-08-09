
const el = wp.element.createElement;
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;

const BLOCKS_TEMPLATE = [
  [ 'core/image', {} ],
  [ 'core/paragraph', { placeholder: 'Image Details' } ],
];

wp.blocks.registerBlockType("sinngrund/kulture-datenbank", {
  title: "Kulture Datenbank Beitrag",
  icon: "paperclip",
  category: "common",
  edit: function () {
    return <h3>This is h3 from JSX.</h3>
    //return wp.element.createElement("h3", null, "Hello, this is from the admin editor screen.");
  //   return el( InnerBlocks, {
  //     template: BLOCKS_TEMPLATE,
  //     templateLock: false,
  // } );

  },
  save: function () {
    return <h3>this is front</h3>
    //return wp.element.createElement("h1", null, "This is the frontend.");
  }
})
