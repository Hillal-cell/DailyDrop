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
} from "./functions.js";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {


     document.addEventListener("dblclick", function (event) {
         // console.log("Clicked element:", event.target);
         handleCastNameClick(event);
     });

    const calendarEl = document.getElementById("calendar");
    const calendar = new Calendar(calendarEl, {
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

            $(info.el).attr({
                'data-bs-toggle': 'tooltip',
                'data-bs-placement': 'top',
                'title': `CastName : ${eventTitle}, 
                Main Cast : ${eventDescription},
                Channel : ${info.event.extendedProps.channel_name}`
            });

            $(info.el).tooltip(); // Initialize the tooltip
        }
    });

    calendar.render();

    const saveEventButton = document.getElementById("save_event_button");
    if (saveEventButton) {
        saveEventButton.removeEventListener("click", saveEvent);
        saveEventButton.addEventListener("click", saveEvent);
    }else{
        console.log("saveEventButton not found");
    }

    const updateEventButton = document.getElementById("update_event_button");
    if (updateEventButton) {
        updateEventButton.removeEventListener("click", handleCastNameClick);
        updateEventButton.addEventListener("click", handleCastNameClick);
    }else{
        console.log("updateEventButton not found");
    }

    const dismissModalButton = document.getElementById("dismiss_modal_button");
    if (dismissModalButton) {
        dismissModalButton.addEventListener("click", function () {
            $("#event_entry_modal").modal("hide");
            location.reload();
        });}
    const dismissModalButton1 = document.getElementById("dismiss_modal_button1");
    if (dismissModalButton1) {
        dismissModalButton1.addEventListener("click", function () {
            $("#event_entry_modal").modal("hide");
            location.reload();
        });}

});
