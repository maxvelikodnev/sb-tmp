# Change Log

## [19.4.2] - 2020-10-02
### Added
- Support for double sided printing
### Fixed
- Template processing on Magento 2.4.1

## [19.4.1] - 2020-08-22
### Added
- Allow integrated label processing to be plugged into
### Fixed
- Added guard condition for product attribute columns

## [19.4.0] - 2020-08-03
### Added
- Expose font subsetting behaviour as a setting

## [19.3.0] - 2020-07-21
### Fixed
- Allow extensions to override page margins
- Support for Php 7.4
- Support for Magento 2.4

## [19.2.0] - 2020-07-16
### Added
- QR Code column

## [19.1.3] - 2020-07-16
### Added
- Support for Multi-Select attributes for product attribute column

## [19.1.2] - 2020-07-10
### Fixed
- Using different fonts per store view

## [19.1.1] - 2020-07-10
### Changed
- Don't render Subtotal/Rowtotal if value is not set

## [19.1.0] - 2020-07-09
### Added
- Barcode source attribute now configurable via a setting
- Logo height now configurable via a setting (update to templates/pdf/logo.phtml)
- Name + Sku Column

## [19.0.0] - 2020-06-22
### Changed
- Dropped active support for Magento 2.1
- Handling of spacing in tables (update to templates/pdf/table.phtml)
### Fixed
- Mageplaza Currency defaults all price columns to 1.00

## [18.4.3] - 2020-06-15
### Changed
- Improved Original Discount Percentage Column calculation
### Fixed
- Upload fields display broken due to Paypal css changes

## [18.4.2] - 2020-05-13
### Fixed
- Provide alternative font encoding option
- Let extra table rows flow onto new page if needed

## [18.4.1] - 2020-04-17
### Fixed
- Not all custom fonts will have character bounding box information

## [18.4.0] - 2020-04-14
### Added
- Product image fallback to parent grouped product if no image was found
### Fixed
- File naming of uploaded custom font
- Changed encoding for improved subsetting

## [18.3.3] - 2020-03-11
### Fixed
- Multi-currency display for some columns when using bundle items

## [18.3.2] - 2020-02-25
### Fixed
- The Tcpdf unicode page number placeholders can be interpreted as Magento templating instructions
- Tweaked spacing for image columns to prevent early page breaks
- Image columns can now be aligned via UI
### Changed
- Adopt latest Magento Coding Standards, all template files have been updated

## [18.3.1] - 2020-01-19
### Changed
- Tweaked discount formatting of original discount percentage column

## [18.3.0] - 2020-01-16
### Added
- Column to display discount percentage based on original price

## [18.2.0] - 2019-11-29
### Added
- Column to display discount percentage
### Changed
- Center align images in column
- Add validation note to margin input fields

## [18.1.2] - 2019-10-31
### Changed
- Removed system value from upload fields with no default

## [18.1.1] - 2019-10-17
### Fixed
- Incorrect CSS for color picker on pre Magento 2.3 versions

## [18.1.0] - 2019-10-05
### Added
- Support for Php 7.3

## [18.0.1] - 2019-08-29
### Fixed
- Image paths are not always consistent
### Added
- Specify default css in an attempt for 3rd party extensions to not remove them

## [18.0.0] - 2019-06-26
### Fixed
- Suppressing the background image for admin print actions
Constructor change in Helper\BackgroundImage
- Custom font display

## [17.0.0] - 2019-05-02
### Fixed
- Improved image fallback for configurable products
### Changed
- Adopt latest Magento Coding Standards, all template files have been updated
Breaking changes in \Block\Pdf\Column\Checkbox, Model\Config\Backend\Customfont, Model\Config\Source\Fonts

## [16.0.0] - 2019-03-25
### Added
- Colour Picker input fields (spectrum based)
- Separate column for displaying fixed product taxes
- Ability to display fixed product taxes inclusive of sales taxes
- Ability to access page information in Footer block
Constructor Change in Block\Pdf\Template\Footer

## [15.0.0] - 2019-02-26
### Added
- Price and Subtotal column with display of Fixed Product Taxes
- Support for RTL locales, switches to Right-To-Left mode for Arabic, Hebrew and Persian
Constructor Change in Model\PdfRenderer

## [14.0.1] - 2019-02-07
### Fixed
- Further Row Total Column adjustments to match Magento with different tax settings

## [14.0.0] - 2019-01-25
### Added
- Ability to use currency formatting with Custom Product Attribute Columns
Constructor Change in Fooman\PdfCore\Block\Pdf\Column\Renderer\ProductAttribute
### Fixed
- Row Total Column should match Magento when using discounts
### Changed
- Removed ability to select Tier Price columns

## [13.1.0] - 2019-01-24
### Added
- Original Price column
### Changed
- Allow SKU product attribute to be displayed as it can be different to the ordered SKU

## [13.0.3] - 2018-12-13
### Fixed
- Ensure Pdf responses are not cached

## [13.0.2] - 2018-11-27
### Changed
- Bold quantity if more than 1 
### Fixed
- Undefined offset notice when printing multiple packing slips with particular pagebreaks

## [13.0.1] - 2018-09-27
### Fixed
- Checkbox image not loading

## [13.0.0] - 2018-09-25
### Security
- Force update to latest Tcpdf library version
- Guard against calls to file_exists for phar stream wrappers
All Constructors using Magento\Framework\Filesystem\Io\File changed to Helper\FileOps
### Changed
- Reorganise unit tests
- Explicitly state all dependencies

## [12.0.1] - 2018-09-13
### Fixed
- Image in Footer may be get flipped due to page constraints

## [12.0.0] - 2018-09-12
### Added
- Ability to set a label row on table
updated view/frontend/templates/pdf/table.phtml
### Fixed
- Null values are not formatted as currency
- Property name clash with parent see Fooman\PdfCore\Block\Pdf\Column\Renderer\Currency

## [11.1.0] - 2018-09-05
### Added
- Ability to specify column alignment

## [11.0.1] - 2018-08-31
### Fixed
- Filename now works for pdf types without increment numbers

## [11.0.0] - 2018-08-06
### Added
- New Column Type: Qty Details
- Background image can now be suppressed from the admin
Constructor Change in \Fooman\PdfCore\Helper\BackgroundImage
### Fixed
- Linebreaks in footer content - updated view/frontend/templates/pdf/footer.phtml

## [10.0.1] - 2018-06-05
### Changed
- Location of custom fonts is now under media/downloadable/pdfcustomfonts

## [10.0.0] - 2018-06-05
### Added
- Ability to upload custom fonts
changed constructor in \Fooman\PdfCore\Model\PdfRenderer

## [9.6.8] - 2018-03-07
### Fixed
- Consistent linebreak on logo placement
- Subtotal column values on invoice and creditmemo

## [9.6.7] - 2017-12-14
### Fixed
- Product attributes can have a trailing underscore
- Footer Font and Size now follow settings

## [9.6.6] - 2017-11-15
### Fixed
- Prevent default array to string conversion

## [9.6.5] - 2017-11-15
### Fixed
- Prevent double decoding of column config

## [9.6.4] - 2017-11-13
### Fixed
- Price Column can now show tax inclusive/exclusive prices simultaneously

## [9.6.3] - 2017-11-13
### Fixed
- Barcode Column handling of deleting products

## [9.6.2] - 2017-11-11
### Fixed
- Columns setting can be pre-populated with default values

## [9.6.1] - 2017-11-08
### Fixed
- Price Column tax in-/exclusiveness in a multi-store environment

## [9.6.0] - 2017-11-03
### Added
- Weight Column

## [9.5.7] - 2017-10-30
### Fixed
- Allow linebreak tag in product attribute column

## [9.5.6] - 2017-10-26
### Fixed
- Image dimensions need to be without unit on php 7.1
- app:config:import with column settings

## [9.5.5] - 2017-10-11
### Added
- PdfRenderer object to fooman_pdfcore_before_write_html event

## [9.5.4] - 2017-10-01
### Fixed
- App Emulation needs to force Locale in the back-end

## [9.5.3] - 2017-09-17
### Changed
- Re-organised tests

## [9.5.2] - 2017-09-12
### Changed
- Template processing

## [9.5.1] - 2017-09-10
### Fixed
- Make Footer template change backward compatible

## [9.5.0] - 2017-09-07
### Added
- Make template variables accessible in the footer
- Support for Php 7.1

## [9.4.0] - 2017-08-22
### Added
- Common Pdf file handler

## [9.3.5] - 2017-08-02
### Fixed
- Empty template during Store Emulation

## [9.3.4] - 2017-07-16
### Changed
- Better compatibility alignment with Magento php versions
- Further spacing improvements

## [9.3.3] - 2017-07-07
### Fixed
- Spacing for barcode column

## [9.3.2] - 2017-06-25
### Changed
- Added supported php versions

## [9.3.1] - 2017-06-24
### Changed
- Improved spacing for images
### Fixed
- Magento template filename resolver caches too aggressively

## [9.3.0] - 2017-06-20
### Added
- Logo block and helper now accept a maxheight argument
### Changed
- Implemented some MEQP2 suggestions
### Fixed
- Magento template filename resolver caches too aggressively

## [9.2.0] - 2017-05-26
### Added
- Ability to display both order and base currencies simultaneously

## [9.1.3] - 2017-05-25
### Fixed
- Image file location on invoices,creditmemos and shipments

## [9.1.2] - 2017-05-16
### Changed
- Tweaked positioning on integrated label

## [9.1.1] - 2017-04-10
### Changed
- Tweaked Image Display

## [9.1.0] - 2017-04-10
### Changed
- Configurable product image now displays the simple product's image
### Added
- New column type: Row Total

## [9.0.2] - 2017-03-11
### Changed
- Nicer formatting of tax percentage column
- Retrieve price from order item
### Fixed
- Footer store id retrieval

## [9.0.1] - 2017-03-01
### Fixed
- Pass through reset param for filename helper

## [9.0.0] - 2017-02-27
### Added
- Emit event in PdfRenderer

## [8.1.0] - 2017-02-23
### Added
- Checkbox Column

## [8.0.2] - 2017-02-13
### Added
- Allow filename to be reset
### Fixed
- Product attribute values on non order pdfs

## [8.0.1] - 2017-02-06
### Fixed
- Alternative approach for cron translation fix

## [8.0.0] - 2017-02-04
### Added
- Add ability to set a row background color
### Fixed
- Explicitly revert emulated design
- Use Image call directly to benefit from automatic scaling
- Workaround for Magento 2 bug to ensure translation of email pdfs via cron
  (Changed DocumentRenderer constructor)

## [7.0.1] - 2016-12-23
### Fixed
- Footer block template can be themed
### Changed
- Allow plugin column listing

## [7.0.0] - 2016-12-20
### Added
- New getForcedPageOrientation() method for DocumentRendererInterface
- Tax Percentage Column

## [6.1.2] - 2016-11-12
### Fixed
- Default fallback for Custom column titles

## [6.1.1] - 2016-11-11
### Fixed
- Tax column uses new currency renderer

## [6.1.0] - 2016-11-06
### Added
- Custom titles for columns
### Fixed
- Price column now follows Magento setting
- Magento changed currency renderer

## [6.0.0] - 2016-10-19
### Changed
- Document Renderer now uses Template directly instead of via a Factory
### Added
- Subtotal excluding tax column

## [5.0.1] - 2016-09-22
### Fixed
- Check to exclude some product attributes

## [5.0.0] - 2016-09-15
### Added
- Background images
### Fixed
- Some settings were not multistore capable 
- getStoreId() added to DocumentRendererInterface

## [4.1.0] - 2016-08-26
### Added
- Support Product Attribute Column

## [4.0.1] - 2016-08-19
### Fixed
-  show column extras in templates/pdf/table.html, adjusted spacing

## [4.0.0] - 2016-07-25
### Added
- Support for integrated labels
- Ability to restore default values
### Changed
- Compatibility with Magento 2.1, for Magento 2.0 use earlier versions
- Columns setting field HtmlId is not random anymore

## [3.0.6] - 2016-06-14
### Fixed
- Multi store capability of footer blocks

## [3.0.5] - 2016-06-09
### Fixed
-  Version Number

## [3.0.4] - 2016-06-09
### Fixed
- Allow setting of currency for table columns
- Compatibility with Magento 2.1.0-rc1
- Page break on table rows
- Constant line height on images
### Changed
- Run footer through template engine

## [3.0.3] - 2016-03-25
### Fixed
- Composer.json for registration and dependency declaration of core modules
- M2 code style

## [3.0.2] - 2016-03-24
### Fixed
- Extra second composer.json to support Magento Setup UI

## [3.0.1] - 2016-03-13
### Fixed
- Code Style improvements

## [3.0.0] - 2016-02-22
### Changed
- Initial Release for Magento 2
