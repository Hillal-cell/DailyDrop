import Alpine from "alpinejs";
import { Calendar } from "fullcalendar";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import multiMonthPlugin from '@fullcalendar/multimonth';

import {
    handleDateClick,
    saveEvent,
    handleCastNameClick,
    clearFormFields,
} from "./functions.js";

window.Alpine = Alpine;
Alpine.start();

const refetchEventsEvent = new CustomEvent('refetchEvents');

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("dblclick", function (event) {
        handleCastNameClick(event);
    });

    const calendarEl = document.getElementById("calendar");
    const calendar = new Calendar(calendarEl, {
        timeZone: "UTC",
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, multiMonthPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek,multiMonthYear",
        },
        events: "/get-events",
        dateClick: handleDateClick,
        eventDidMount: function (info) {
            const eventTitle = info.event.title;
            const eventDescription = info.event.extendedProps.description;
            const type_of_control = info.event.extendedProps.typeOfControl;

            $(info.el).attr({
                'data-bs-toggle': 'tooltip',
                'data-bs-placement': 'top',
                'title': `${type_of_control} : ${eventTitle}, 
                Producer/Artist : ${eventDescription},
                Channel : ${info.event.extendedProps.channel_name}`,
                'data-bs-custom-class': 'custom-tooltip' // Add custom class here
            });

            $(info.el).tooltip(); // Initialize the tooltip
        }
        
    });

    calendar.render();

     // Listen for custom event to refetch calendar events
     window.addEventListener('refetchEvents', function () {
        calendar.refetchEvents();
    });

    const saveEventButton = document.getElementById("save_event_button");
    if (saveEventButton) {
    //    saveEventButton.removeEventListener("click", saveEvent);
    //    console.log("saveEventButton found");
        saveEventButton.addEventListener("click", saveEvent);
        window.dispatchEvent(new CustomEvent('refetchEvents'));
    } else {
        console.log("saveEventButton not found");
    }


    const dismissModalButton = document.getElementById("dismiss_modal_button");
    if (dismissModalButton) {
        dismissModalButton.addEventListener("click", function () {
            $("#event_entry_modal").modal("hide");
            location.reload();
        });
    }
    const dismissModalButton1 = document.getElementById("dismiss_modal_button1");
    if (dismissModalButton1) {
        dismissModalButton1.addEventListener("click", function () {
            $("#event_entry_modal").modal("hide");
            location.reload();
            
        });
    }

    
    
});
export { refetchEventsEvent };





