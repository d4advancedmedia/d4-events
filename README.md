# Events
Wordpress Events Plugin
A simple events plugin that includes an events calendar, events listings, Add To Calendar capabilities, and other essential event functions.

<h2>Adding Events:</h2>
Events are added via the Wordpress admin area. To create a new event:
<ol>
<li>Hover over the "Events" menu item and select "Add New".</li>
<li>Add a Title (required) and Description.</li>
<li>Set the Start Date and End Date in the Details section. It is important that you select start and end dates for all events. Events will default to the current date for the start and end date. If you leave either of these fields empty, then it will be filled in using the value from the other field. For example, if you enter 1/2/2017 as a Start Date, but leaves the End Date blank, the End Date will be set as 1/2/2017. It will also check to see if the End Date is later than the Start Date. For example, if the user enters 1/2/2017 as a Start Date, and enters 1/1/2017 as an End Date, the End Date will be set as 1/2/2017.</li>
<li>Enter the Start/End times using the following format: 12:00pm.</li>
<li>Select the Timezone for the event (this is used for the Add to Calendar feature). It will default to your current Wordpress timezone setting.</li>
<li>Add a location. If you see an error code in this area, it means that you need to enable the Google Places API by adding the domain to the list of valid referrers.</li>
<li>Add a registration link, if necessary.</li>
<li>Set other options such as "Repeating" event options, if necessary.</li>
<li>Add a Featured Image, if necessary.</li>
<li>Add Files - this is useful for meetings and other events that have files associated with them.</li>
<li>Publish the event! You can now view your event via the event permalink.</li>
</ol>

<h2>Creating a Calendar or List of events:</h2>
<ol>
<li>Create a new page or post, and insert the events shortcode: [events]</li>
<li>Add shortcode attributes. Note that most of these are for list-style only. Possible options include:
<table>
<tr><td>Name</td><td>Options/Examples</td><td>Description</td></tr>
<tr><td>year</td><td>ex: 2008</td><td>Sets the year of the calendar to display</td></tr>
<tr><td>month</td><td>ex: January</td><td>Sets the month of the calendar to display</td></tr>
<tr><td>category</td><td>ex: Swim Meets,Swim Practice</td><td>Comma separated list of Wordpress Event Category Names to display</td></tr>
<tr><td>exclude_category</td><td>ex: 128</td><td>Comma separated list of Wordpress Event Category IDs to hide</td></tr>
<tr><td>agenda</td><td>true/false</td><td>Forces mobile view</td></tr>
<tr><td>style</td><td>calendar(default), list</td><td>Displays the events either as a calendar or a list. Note that for now, repeating events only show up multiple times in calendar view.</td></tr>
<tr><td>links</td><td>true/false</td><td>Choose to disable links to single event pages (usefull for meetings and similar event types</td></tr>
<tr><td>files</td><td>ex: agenda,minutes,other</td><td>Comma-separated list of file categories to display. REQUIRED to display files in list view.</td></tr>
<tr><td>range</td><td>all(default), past, future</td><td>Select which events to display. Helpful for showing only upcoming events or past events</td></tr>
<tr><td>number</td><td>ex: 3</td><td>Limit the number of events to display</td></tr>
<tr><td>thumbnail_size</td><td>thumbnail(default),medium,large,full</td><td>Choose the featured image size to display in list view</td></tr>
</table>
</li>
