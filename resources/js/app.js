import Alpine from "alpinejs";
import { Calendar } from "fullcalendar";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import {
    handleDateClick,
    saveEvent,
    handleCastNameClick,
    handleSaveResponse,
    handleSaveError,
    handleUpdateResponse,
    handleUpdateError,
} from "./functions.js";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    // Attach click event listener to cast name
    // const castNameField = document.getElementById("cast_name");
    // castNameField.addEventListener("click", handleCastNameClick);

    // document.addEventListener("click", function (event) {
    //     console.log("Clicked element:", event.target);
    // });

    const calendarEl = document.getElementById("calendar");
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek",
        },
        
        events: "/get-events",
        channel:"/channel/{channelName}",
        dateClick: handleDateClick,
    });

    calendar.render();

    // Bind the saveEvent function to the button click event only once
    const saveEventButton = document.getElementById("save_event_button");
    if (saveEventButton) {
        saveEventButton.removeEventListener("click", saveEvent); // Remove any existing event listener
        saveEventButton.addEventListener("click", saveEvent);
    }
});

