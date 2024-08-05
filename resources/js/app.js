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

const refetchEventsEvent = new CustomEvent('refetchEvents');

document.addEventListener("DOMContentLoaded", function () {
   
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
            const channel_name = info.event.extendedProps.channel_name;
            
            if (channel_name === "Bangers") {
                info.el.style.backgroundColor = "#ff1100";
            } else if (channel_name === "Diaspora") {
                info.el.style.backgroundColor = "#ee00ff";
            } else if (channel_name === "Zamani") {
                info.el.style.backgroundColor = "#3357FF";
            } else if (channel_name === "Muziki 256") {
                info.el.style.backgroundColor = "#06ff00";
            }else if (channel_name === "Prayze") {
                info.el.style.backgroundColor = "#ff00c8"; 
            }else if (channel_name === "Emikolo") {
                info.el.style.backgroundColor = "#ff8c00";
            }else if (channel_name === "Filimu") {
                info.el.style.backgroundColor = "#00ffea";
            }else {
                info.el.style.backgroundColor = "#ff00c8";
            }

            
            $(info.el).attr({
                'data-bs-toggle': 'tooltip',
                'data-bs-placement': 'top',
                'title': `${type_of_control} : ${eventTitle}, 
                Producer/Artist : ${eventDescription},
                Channel : ${info.event.extendedProps.channel_name}`,
                
                
            });

            $(info.el).tooltip(); // Initialize the tooltip

            // Add double-click event listener to the event element
            info.el.addEventListener("dblclick", function () {
                handleCastNameClick(eventTitle);
                
            });
        }
        
    });

    calendar.render();

     // Listen for custom event to refetch calendar events
     window.addEventListener('refetchEvents', function () {
        calendar.refetchEvents();
    });

    const saveEventButton = document.getElementById("save_event_button");
    if (saveEventButton) {
    
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





