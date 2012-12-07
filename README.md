# Zenbu MX Cloner
Adds [MX Cloner](http://devot-ee.com/add-ons/mx-cloner) support to Zenbu.

This extension for the [Zenbu](http://zenbustudio.com/software/zenbu/) add-on adds a column for the [MX Cloner](http://devot-ee.com/add-ons/mx-cloner) add-on as an extra column in Zenbu.

This add-on is also a teaching aid/example for developers who wish to modify the display of Zenbu column data using the following hooks:

- **zenbu_add_column**
- **zenbu_entry_cell_data** 
- **zenbu_custom_order_sort**

## Requirements

- ExpressionEngine 2.x
- Zenbu 1.5.5+ (Get it [here](http://zenbustudio.com/software/zenbu/))
- [MX Cloner](http://devot-ee.com/add-ons/mx-cloner) (You could run this add-on without MX Cloner, but it won't actually *clone* entries)


## Installation

1. Download the add-on, and rename the folder to **zenbu_mx_cloner**
2. Place the **zenbu_mx_cloner** folder into your ExpressionEngine's third_party folder (typically found at system/expressionengine/third_party)
3. In the Control Panel, under Add-ons => Extensions, click on the "Enable" link next to **Zenbu MX Cloner Extension**
4. Enable the column display from Zenbu "Display settings" section.