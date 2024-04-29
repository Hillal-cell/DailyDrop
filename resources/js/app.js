import "./bootstrap";
import Alpine from "alpinejs";
import { Calendar } from "fullcalendar";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek",
        },
        // Fetch events from the server
        events: "/get-events",
        // Add event listener for date click
        dateClick: handleDateClick,
    });

    calendar.render();

    // Function to handle date click event
    function handleDateClick(info) {
        const date = info.dateStr;
        const time = prompt("Enter time (HH:mm):");
        const activity = prompt("Enter activity:");

        if (time !== null && activity !== null) {
            const dateTime = combineDateTime(date, time);
            saveActivityToServer(dateTime, activity);
        }
    }

    // Function to combine date and time into ISO 8601 format
    function combineDateTime(date, time) {
        return date + "T" + time + ":00";
    }

    // Function to save activity to the server
    function saveActivityToServer(dateTime, activity) {
        fetch("/save-activity", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                dateTime: dateTime,
                activity: activity,
            }),
        })
            .then(handleSaveResponse)
            .catch(handleSaveError);
    }

    // Function to handle the response of saving activity
    function handleSaveResponse(response) {
        if (response.ok) {
            console.log("Activity saved successfully");
            calendar.refetchEvents(); // Refresh the events from the server
        } else {
            console.error("Failed to save activity");
        }
    }

    // Function to handle errors during save activity
    function handleSaveError(error) {
        console.error("Error occurred during fetch request:", error);
    }
});

import "./app";
