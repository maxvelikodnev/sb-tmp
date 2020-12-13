# Change Log

## [116.3.2] - 2020-10-28
### Fixed
- Stripe payment method and admin ordering
- Provide design to bundle item table

## [116.3.1] - 2020-10-07
### Added
- Compatibility with Magento 2.3.6 and 2.4.1

## [116.3.0] - 2020-08-14
### Added
- Integrated label option with custom template support

## [116.2.0] - 2020-07-28
### Added
- Compatibility with Magento 2.4.0
- Support for Php 7.4

## [116.1.1] - 2020-07-22
### Fixed
- Custom product attributes weren't loaded for bundle products using Design One

## [116.1.0] - 2020-07-10
### Added
- Improved integration with Fooman OrderManager print actions
- Improved table spacing (updates to templates/pdf-alt/table.phtml and templates/pdf-design-1/table.phtml)
### Fixed
- Currency symbol on multi-store, multi-currency bundle (thanks Vladimir)

## [116.0.0] - 2020-06-26
### Added
- Workaround for Magento 1 orders migrated without tax information
### Changed
- Updated minimum dependencies to match Magento 2.2

## [115.5.1] - 2020-06-11
### Changed
- Add feature flag for alternative display of configurables

## [115.5.0] - 2020-05-14
### Changed
- Customer Notes are now displayed as comment by default

## [115.4.2] - 2020-05-13
### Fixed
- Alternative Designs should display bundle selection labels (template update in pdf-alt/table.phtml)

## [115.4.1] - 2020-04-23
### Fixed
- Dependency coverage on older Magento versions

## [115.3.1] - 2020-04-23
### Fixed
- Dependency coverage on older Magento versions

## [115.4.0] - 2020-04-17
### Added
- Ability to add content for each pdf based on customer group (template file changes for all designs)
- Ability to display product category names as column
- Workaround for PostNL packingslips and bundles
### Changed
- Moved svg content into PdfDesign package (template file change for DesignOne)

## [115.3.0] - 2020-04-10
### Security
- Admin comments on shipments may be falsely included in other shipment pdfs

## [115.2.1] - 2020-03-12
### Added
- Print Packingslip button on Order View

## [115.2.0] - 2020-03-02
### Added
- Ability for 3rd party extensions to add comments using event fooman_pdfcustomiser_collect_custom_comments (template update in comments.phtml)
### Fixed
- Improve Paypal Plus Payment method display (template update in payment-default.phtml)

## [115.1.2] - 2020-01-23
### Changed
- Adapted for latest Email Attachments
- Adopt latest Magento Coding Standards

## [115.1.1] - 2019-11-29
### Fixed
- Column alignment can't be overridden in Alternative Designs and DesignOne (table.phtml)
- Improve compatibility with 3rd party custom pdf totals extensions

## [115.1.0] - 2019-10-05
### Added
- Support for Php 7.3
- Support for Magento 2.3.3
### Fixed
- Tax inclusive shipping amounts in Totals when displaying two currencies

## [115.0.2] - 2019-09-27
### Fixed
- Don't render in adapters if module is missing

## [115.0.1] - 2019-09-26
### Added
- Extra messaging to enable Fooman_PdfDesign

## [115.0.0] - 2019-09-24
### Changed
- Extracted design related code into fooman/pdfdesign-m2 package
Constructor change in Block\AbstractSalesDocument, Block\Table and Block\Table\BundleHandler
- Custom designs now need to be defined via fooman_pdfdesign.xml instead of pdfcustomiserdesign.xml
- Magento 2.1 branch no longer receives new features
### Fixed
- Totals width when displayed with two currencies in DesignOne templates
- Tax inclusive amounts in Totals when displaying two currencies
Constructor change in Block\Totals

## [114.1.0] - 2019-08-29
### Added
- New Alternative Design Two

## [114.0.1] - 2019-08-14
### Fixed
- DesignOne don't display empty store address
- Alternative Design display a header box on single address pdfs

## [114.0.0] - 2019-08-09
### Changed
- Tweaks to DesignOne and Alternative Design, updates to templates/pdf-alt and templates/pdf-design-1
- Alternative Design now uses its own totals.phtml template file
- Constructor change in Model\AlternativeDesign
### Added
- Colour Picker for Alternative Design

## [113.2.3] - 2019-06-26
### Added
- Duplicate nodes of weee/pdf.xml to cover Composer replace scenarios
### Fixed
- Include PdfCore fix for custom fonts

## [113.2.2] - 2019-06-24
### Fixed
- Align some terms to existing translations
- Type hints
- List all direct dependencies

## [113.2.1] - 2019-05-13
### Fixed
- Double up of dynamic priced bundle items on invoices

## [113.2.0] - 2019-05-13
### Fixed
- Added resilience to display of payment blocks and totals, Magento does not always store applied taxes
- Include details from Offline payment methods in payment block
### Changed
- Adopt latest Magento Coding Standards, all template files have been updated
- Convert controller authentication to ADMIN_RESOURCE

## [113.1.0] - 2019-04-23
### Changed
- Payment information is now provided via a PdfCustomiser template file
### Fixed
- Minor design tweaks to totals.phtml template

## [112.0.1] - 2019-04-17
### Security
- Fixes incorrect Guest Order validation to prevent information disclosure

## [113.0.0] - 2019-03-26
### Changed
- Api\DesignInterface changes getFooterLayoutHandle(), getStoreId(), setStoreId() added
### Added
- New Design with interactive colour selector
### Fixed
- Tax Table Basis for discounted items
- Bundle total columns display tax component amount only

## [112.0.0] - 2019-02-26
### Added
- Support for displaying Fixed Product Taxes
- Support for RTL locales, switches to Right-To-Left mode for Arabic, Hebrew and Persian
Constructor Change in Block\AbstractSalesDocument
### Fixed
- Tax table for items with multiple tax rates
Constructor change in Block\TaxTable

## [111.0.3] - 2019-02-21
### Fixed
- Filter out credit memo adjustments on order pdfs
- Workaround for Magento loading order tax rates for creditmemos

## [111.0.2] - 2019-02-09
### Fixed
- Don't display zero qty items in In Print Order as Packingslip mode
- Display of bundle items on Packingslips

## [111.0.1] - 2019-01-25
### Added
- PdfCore: Ability to use currency formatting with Custom Product Attribute Columns
### Fixed
- PdfCore: Row Total Column should match Magento when using discounts

## [111.0.0] - 2019-01-25
### Added
- New mode to switch display of fixed bundle items
Changed Constructor in Fooman\PdfCustomiser\Helper\BundleProductItem
### Fixed
- In Print Order as Packingslip mode adjust ordered quantities to reflect cancelled and refunded items
- Render currency in custom product attribute columns with price input

## [110.0.2] - 2019-01-24
### Fixed
- Custom Product Attributes for bundle items
- Locale specific date formatting for order comment history
- Always apply improved tax rate display

## [110.0.1] - 2018-12-10
### Fixed
- Sorting by product attributes on non-order pdfs

## [110.0.0] - 2018-11-29
### Added
- Support for Magento 2.3
### Changed
- Improved display of tax rates, only display individual tax rates when "Display Full Tax Summary" is selected
Constructor change in Block\Totals

## [109.3.0] - 2018-11-14
### Added
- Setting to display weight on pdfs

## [109.2.3] - 2018-10-26
### Fixed
- Ensure dates are displayed using store's timezone

## [109.2.2] - 2018-10-23
### Changed
- Pull in latest version of PdfCore to include security update

## [109.2.1] - 2018-09-12
### Changed
- Improved display of bundles

## [109.2.0] - 2018-09-10
### Added
- Ability to define alignment of each column
- New utility method to display different date and time formats

## [109.0.0] - 2018-08-16
### Fixed
- All controllers now remove temporary pdf files under var/
Constructor Changes for Controller\Adminhtml
### Changed
- Privatised properties on Controller\Adminhtml

## [108.3.3] - 2018-08-13
### Changed
- Include latest Pdf Core, New Column Type: Qty Details
- Reorganised unit tests

## [108.3.2] - 2018-07-18
### Changed
- Include latest Email Attachments release

## [108.3.1] - 2018-06-29
### Fixed
- Adjust new Print Invoices from Shipment grid action to new UI Component

## [108.3.0] - 2018-06-05
### Added
- Ability to upload custom fonts

## [108.2.2] - 2018-05-31
### Added
- Ability to print invoices from shipment grid

## [108.2.1] - 2018-05-03
### Fixed
- Remove extra whitespace in front of custom text
- Add linebreaks to payment instructions

## [108.2.0] - 2018-03-21
### Changed
- Updated to latest Email Attachments release 3.0.0
### Added
- Terms and Conditions when attached are now also transformed into a pdf
- Ability to sort line items by column

## [108.1.0] - 2018-03-13
### Added
- Ability to display weight of document items on pdf
(update shipping.phtml if previously customised)
### Fixed
- Integrated label setting on shipments when using the Print Order as Packingslip option

## [108.0.3] - 2018-02-11
### Fixed
- Multiply bundle quantities
(update view/frontend/templates/pdf/table/bundle-extras if previously customised)

## [108.0.2] - 2017-12-08
### Fixed
- Magento resource ids are not consistent

## [108.0.1] - 2017-11-20
### Changed
- Stabilise functional tests

## [108.0.0] - 2017-11-19
### Changed
- Package name renamed to fooman/pdfcustomiser-implementation-m2, installation should be via metapackage fooman/pdfcustomiser-m2
- Increased version number by 100 to differentiate from metapackage
### Added
- Framework for Designs
- Alternative Design

## [7.2.3] - 2017-11-15
### Added
- Pass through Order object to shipping template

## [7.2.2] - 2017-11-15
### Fixed
- Update to latest PdfCore, don't decode column setting twice

## [7.2.1] - 2017-11-12
### Fixed
- Columns are now pre-populated with default values

## [7.2.0] - 2017-11-01
### Changed
- Table extra information is now rendered through a separate block
### Fixed
- Quantity and Status Column
- getStoreId() is not available on all payment methods

## [7.1.1] - 2017-10-25
### Changed
- Tax total is now displayed after shipping

## [7.1.0] - 2017-10-02
### Added
- Ability to display detailed tax table
(update view/frontend/templates/pdf/order.phtml, invoice.phtml or creditmemo.phtml if previously customised)

## [7.0.0] - 2017-09-16
### Added
- Support for PHP 7.1
- Support for Magento 2.2

## [6.0.0] - 2017-08-22
### Added
- Print Order as Packing Slip
### Changed
- Use new PdfCore File Handler, cleans temp pdf files under var/ 

## [5.1.2] - 2017-08-04
### Fixed
- Make comments more resilient against faulty input
### Changed
- Implement MEQP2 suggestions

## [5.1.1] - 2017-07-23
### Fixed
- Align packing slip columns with Magento calculated values
- Improved empty row check
- Ability to create pdfs for deleted store views

## [5.1.0] - 2017-05-26
### Added
- Ability to display order and base currency simultaneously
(update view/frontend/templates/pdf/totals.phtml if previously customised)

## [5.0.6] - 2017-05-16
### Fixed
- Width of gift message peel off label

## [5.0.5] - 2017-04-12
### Changed
- Improved display for custom product attributes

## [5.0.4] - 2017-02-28
### Fixed
- PdfRenderer attachment file naming

## [5.0.3] - 2017-02-27
### Fixed
- Bundle Products not displaying custom options

## [5.0.2] - 2017-02-27
### Changed
- Widen dependency for Pdf Core

## [5.0.1] - 2017-02-18
### Fixed
- Do not set shipment tracking information if no tracks exist

## [5.0.0] - 2017-02-13
### Added
- Enable Tcpdf tags in all pdfs
### Changed
- Allow PdfRenderer adapters to be run multiple times to increase compatibility

## [4.0.0] - 2017-02-06
### Fixed
- Update to latest PdfCore, includes Cron translation fix, AbstractSalesDocument constructor changed

## [3.6.4] - 2017-01-10
### Fixed
- deal with Magento's inconsistent shipment ACL

## [3.6.3] - 2016-12-23
### Changed
- Bundle items are now displayed as a group

## [3.6.2] - 2016-12-21
### Fixed
- Only show shipment specific tracking information on packing slip
- Product attribute column values are retrieved from underlying simple products for configurable products 

## [3.6.1] - 2016-11-15
### Fixed
- Order View Authentication on frontend pdfs

## [3.6.0] - 2016-11-11
### Added
- Display Gift Messages
- Replace invoice, shipment and creditmemo pdfs accessible from single order view 

## [3.5.0] - 2016-10-21
### Added
- Store Owner Address field is now parsed for custom variables

## [3.4.0] - 2016-10-19
### Added
- Custom Text fields are now parsed for custom variables, for example {{var order.getCustomerId}}
### Changed
- Permission check changed -if you can view the sales object you can print it

## [3.3.3] - 2016-10-02
### Fixed
- Change template reference to $block

## [3.3.2] - 2016-09-16
### Fixed
- Multi store support for logos

## [3.3.1] - 2016-09-15
### Fixed
- Some settings were not multistore capable

## [3.3.0] - 2016-08-26
### Added
- Columns for Product Attributes
- Replace print actions in the customer frontend with pdfs 

## [3.2.0] - 2016-08-20
### Added
- Display Order Comments in the pdf (<?php echo $block->getCommentsBlock() ?> added in pdf template files)

## [3.1.0] - 2016-08-19
### Added
- Support displaying Custom Product Options

## [3.0.0] - 2016-07-25
### Added
- Ability to restore default values
- Support for Integrated Labels

## [2.1.3] - 2016-07-13
### Changed
- Compatibility with Magento 2.1, for Magento 2.0 use earlier versions

## [2.1.2] - 2016-06-10
### Fixed
-  Multi currency display in item table

## [2.1.1] - 2016-04-05
### Changed
- Improved display of bundle items

## [2.1.0] - 2016-04-04
### Added
- Bundle Email Attachments to allow pdfs to be attached to outgoing emails

## [2.0.1] - 2016-02-22
### Fixed
- Extra second composer.json to support Magento Setup UI

## [2.0.0] - 2016-02-16
### Changed
- Change folder structure to src/ and tests/ (not applicable to Marketplace release)
### Fixed
- Use correct function for translation
- Virtual orders

## [1.0.4] - 2016-01-01
### Changed
- Implemented Audit Program feedback
