<style >
    .v-event{
        font-size : 1.2em;
        color     : #ffffff!important;
    }
    .v-event div{
        font-size : 1.1em;
        color     : #ffffff!important;
    }
    .v-event strong{
        display : none;
    }
    .theme--light.v-calendar-weekly .v-calendar-weekly__day{
        color     : #ffffff!important;
        font-size : .8em!important;
    }
    .v-present .v-btn{
        background-color : #1867c0 !important;
        border-color     : #1867c0 !important;
        margin-bottom    : 1px;
    }
    .v-event-timed{
        padding   : 10px;
        font-size : 1em!important;
        color     : #ffffff!important;
    }
    .v-event-timed div{
        color : transparent!important;
    }
    .v-event-timed div strong{
        color : #ffffff!important;
    }
</style>
<template>
    <app-layout>
        <template #header>
            <h2 class="h4 font-weight-bold mt-3">
                Calendario
            </h2>
        </template>
        <div class="row mb-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row m-1">
                            <div class="col-12 col-md-6 card-title">
                                <!-- Crear evento -->
                                <button 
                                    class  = "btn btn-outline-primary btn-sm"
                                    @click = "showModalAddEvent()"
                                    >
                                    <span>Agregar evento</span>
                                </button>
                                &nbsp;
                                <!-- Titulo del evento -->
                                <strong 
                                    v-if   = "$refs.calendar"
                                    style  = "text-transform:capitalize"
                                    v-html = "$refs.calendar.title"
                                    >
                                </strong>
                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-end align-items-end">
                                <!-- Boton Hoy -->
                                <button 
                                    class  = "btn btn-primary"
                                    @click = "setToday">
                                    Hoy
                                </button>
                                 <!-- Boton Avanzar -->
                                <button 
                                    class  = "btn btn-default"
                                    @click = "prev"
                                    >
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                 <!-- Boton Regresar -->
                                <button 
                                    class  = "btn btn-default"
                                    @click = "next"
                                    >
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <div class="dropdown">
                                    <button 
                                        class         = "btn btn-default dropdown-toggle" 
                                        type          = "button" 
                                        id            = "calendarDropdown" 
                                        data-toggle   = "dropdown" 
                                        aria-haspopup = "true" 
                                        aria-expanded = "false"
                                        >
                                        <span v-html="typeToLabel[type]"></span>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="calendarDropdown">
                                        <!-- Mostrar por día -->
                                        <button 
                                            class ="dropdown-item" 
                                            @click="type = 'day'"   
                                            >
                                            Día
                                        </button>
                                        <!-- Mostrar por semana -->
                                        <button 
                                            class="dropdown-item" 
                                            @click="type = 'week'">
                                            Semana
                                        </button>
                                        <!-- Mostrar por mes  -->
                                        <button 
                                            class="dropdown-item" 
                                            @click="type = 'month'">
                                            Mes
                                        </button>
                                        <!-- Mostrar por 4 días  -->
                                        <button 
                                            class="dropdown-item" 
                                            @click="type = '4day'">
                                            4 días
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <v-sheet height="600">
                            <v-calendar
                                ref          = "calendar"
                                v-model      = "focus"
                                color        = "primary"
                                :events      = "events"
                                :event-color = "getEventColor"
                                :type        = "type"
                                @click:event = "showEvent"
                                @click:more  = "viewDay"
                                @click:date  = "viewDay"
                                @change      = "updateRange"
                                :locale      = "locale"
                                :event-name  = "getEventTitle"
                                >
                            </v-calendar>
                        </v-sheet>
                    </div>
                </div>
                <!-- Modal Agregar Evento -->
                <div class="modal fade" id="modalAddEvent" tabindex="-1" role="dialog" aria-labelledby="modalAddEvent" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" :style="{'background-color':'#5d6ad0', 'color':'#fff'}">
                                <h5 class="modal-title">
                                    Agregar Evento
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit="addEvent" method="post">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input
                                            name             = "name"
                                            id               = "name"
                                            class            = "form-control"
                                            :class           = "{'is-invalid' : errors.name}"
                                            placeholder      = "Nombre del evento"
                                            v-model          = "event.name"
                                            aria-describedby = "name-live-feedback"
                                            required         = "required"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Descripción</label>
                                        <textarea
                                            name             = "description"
                                            id               = "description"
                                            class            = "form-control"
                                            :class           = "{'is-invalid' : errors.description}"
                                            placeholder      = "Descripción"
                                            v-model          = "event.description">
                                        </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Inicio</label>
                                        <input
                                            type        = "date"
                                            name        = "start_date"
                                            id          = "datepicker-start_date"
                                            v-model     = "event.startDate"
                                            class       = "form-control"
                                            :class      = "{'is-invalid' : errors.start_date}"
                                            placeholder = "Fecha de inicio"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="start_hour">Hora de inicio</label>
                                        <input
                                            type             = "time"
                                            name             = "start_hour"
                                            id               = "datepicker-start_hour"
                                            v-model          = "event.startHour"
                                            class            = "form-control"
                                            :class           = "{'is-invalid': errors.start_hour}"
                                            aria-describedby = "start_hour-live-feedback"
                                            placeholder      = "Hora de inicio"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">Final</label>
                                        <input
                                            type        = "date"
                                            name        = "end_date"
                                            id          = "datepicker-end_date"
                                            v-model     = "event.endDate"
                                            class       = "form-control"
                                            :class      = "{'is-invalid' : errors.end_date}"
                                            placeholder = "Fecha final"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="end_hour">Hora final</label>
                                        <input
                                            type             = "time"
                                            name             = "end_hour"
                                            id               = "datepicker-end_hour"
                                            v-model          = "event.endHour"
                                            class            = "form-control"
                                            :class           = "{'is-invalid': errors.end_hour}"
                                            aria-describedby = "end_hour-live-feedback"
                                            placeholder      = "Hora final"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="color">Color</label>
                                        <input
                                            type        = "color"
                                            name        = "color"
                                            id          = "color"
                                            class       = "form-control text-break text-wrap bg-transparent text-muted"
                                            :class      = "{'is-invalid' : errors.color}"
                                            placeholder = "Color"
                                            v-model     = "event.color"
                                        />
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group">
                                    <button 
                                        type   = "submit"
                                        class  = "btn btn-primary btn-sm mr-1"
                                        @click = "addEvent()"
                                        >
                                        Agregar
                                    </button>
                                    <button 
                                        type         = "button"
                                        class        = "btn btn-secondary btn-sm"
                                        data-dismiss = "modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Final Modal Agregar Evento -->
                <!-- Modal Editar Evento -->
                <div class="modal fade" id="modalEvent" tabindex="-1" role="dialog" aria-labelledby="modalEvent" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <!-- Personalizado de acuerdo al color del evento -->
                            <div
                                class  = "modal-header text-white font-weight-bold"
                                :style = "{'background-color':event.color}"
                            >
                                <h5 class="modal-title" id="editEvent">

                                    <!-- Boton eliminar evento -->
                                    <button
                                        @click = "showDeleteEvent = true; showUpdateEvent = false;"
                                        class  = "text-white mr-3"
                                        >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Final Boton eliminar evento-->
                                    <!-- Titulo del evento -->
                                    <span v-html="event.name"></span> 
                                </h5>
                                <!-- Cerrar modal -->
                                <button
                                    type         = "button"
                                    class        = "close text-white"
                                    data-dismiss = "modal"
                                    aria-label   = "Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Información del evento -->
                                <div v-if = " ! showUpdateEvent && ! showDeleteEvent">
                                    <h3 v-html="event.description"></h3>
                                    <h5>
                                        De <span v-html="event.start"></span>                                        
                                        a <span v-html="event.end"></span>
                                    </h5>
                                </div>
                                <!-- Formulario para ediar el evento -->
                                <div v-if="showUpdateEvent">
                                    <form @submit="updateEvent" method="post">
                                        <div class="form-group">
                                            <label for="name">Nombre</label>
                                            <input
                                                name             = "name"
                                                id               = "name"
                                                class            = "form-control"
                                                :class           = "{'is-invalid' : errors.name}"
                                                placeholder      = "Nombre del evento"
                                                v-model          = "event.name"
                                                aria-describedby = "name-live-feedback"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descripción</label>
                                            <textarea
                                                name        = "description"
                                                id          = "description"
                                                class       = "form-control"
                                                :class      = "{'is-invalid' : errors.description}"
                                                placeholder = "Descripción"
                                                v-model     = "event.description"
                                                >
                                            </textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Inicio</label>
                                            <input
                                                type        = "date"
                                                name        = "start_date"
                                                id          = "datepicker-start_date"
                                                v-model     = "event.startDate"
                                                class       = "form-control"
                                                :class      = "{'is-invalid' : errors.start_date}"
                                                placeholder = "Fecha de inicio"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="start_hour">Hora de inicio</label>
                                            <input
                                                type             = "time"
                                                name             = "start_hour"
                                                id               = "datepicker-start_hour"
                                                v-model          = "event.startHour"
                                                class            = "form-control"
                                                :class           = "{'is-invalid': errors.start_hour}"
                                                aria-describedby = "start_hour-live-feedback"
                                                placeholder      = "Hora de inicio"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">Final</label>
                                            <input
                                                type        = "date"
                                                name        = "end_date"
                                                id          = "datepicker-end_date"
                                                v-model     = "event.endDate"
                                                class       = "form-control"
                                                :class      = "{'is-invalid' : errors.end_date}"
                                                placeholder = "Fecha final"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="end_hour">Hora final</label>
                                            <input
                                                type             = "time"
                                                name             = "end_hour"
                                                id               = "datepicker-end_hour"
                                                v-model          = "event.endHour"
                                                class            = "form-control"
                                                :class           = "{'is-invalid': errors.end_hour}"
                                                aria-describedby = "end_hour-live-feedback"
                                                placeholder      = "Hora final"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="color">Hora final</label>
                                            <input
                                                type        = "color"
                                                name        = "color"
                                                id          = "color"
                                                class       = "form-control text-break text-wrap bg-transparent text-muted"
                                                :class      = "{'is-invalid' : errors.color}"
                                                placeholder = "Color"
                                                v-model     = "event.color"
                                            />
                                        </div>
                                    </form>
                                </div>
                                <!-- Final del formulario para actualizar Evento-->
                                <!-- Texto Eliminar evento -->
                                <div v-if="showDeleteEvent">
                                    <h4>¿Está seguro de eliminar el evento?</h4>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botones información evento -->
                                <div
                                    class = "btn-group btn-group-sm"
                                    v-if  = "! showUpdateEvent && ! showDeleteEvent">
                                    <button
                                        type   = "button"
                                        class  = "btn btn-outline-info btn-sm"
                                        @click = "mtdShowUpdateEvent(event)"
                                        >
                                        <i class="fas fa-edit"></i>
                                        <span>Editar</span>
                                    </button>
                                    <button
                                        type   = "button"
                                        class  = "btn btn-outline-secondary btn-sm"
                                        @click = "hideModalEvent"
                                        >
                                        <span>Cerrar</span>
                                    </button>
                                </div>
                                <!-- Final Botones información evento -->
                                <!-- Botones ediciones del evento -->
                                <div
                                    class = "btn-group btn-group-sm"
                                    v-if  = "showUpdateEvent"
                                    >
                                    <button
                                        type  ="button"
                                        class ="btn btn-outline-info btn-sm"
                                        @click="updateEvent(event)"
                                        >
                                        <i class="fas fa-edit"></i>
                                        <span>Actualizar</span>
                                    </button>
                                    <button
                                        @click = "showUpdateEvent = false"
                                        type   = "button"
                                        class  = "btn btn-outline-danger btn-sm"
                                        >
                                        <span>Cancelar</span>
                                    </button>
                                </div>
                                <!--  Botones eliminar -->
                                <div
                                    class = "btn-group btn-group-sm"
                                    v-if  = "showDeleteEvent && ! showUpdateEvent"
                                    >
                                    <button
                                        type   = "button"
                                        class  = "btn btn-outline-danger btn-sm mr-1"
                                        @click = "deleteEvent(event)"
                                        >
                                        <i class="fas fa-trash-alt"></i>
                                        Si, eliminar
                                    </button>
                                    <button
                                        @click = "showDeleteEvent = false"
                                        type   = "button"
                                        class  = "btn btn-outline-secondary btn-sm"
                                        >
                                        No, cancelar
                                    </button>
                                </div>
                                <!-- Final Botones eliminar -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Vuetify, {
        VApp,
        VBtn,
        VCalendar,
        VCard,
        VCardActions,
        VCardText,
        VCol,
        VIcon,
        VMenu,
        VList,
        VListItem,
        VListItemTitle,
        VRow,
        VSpacer,
        VSheet,
        VToolbar,
        VToolbarTitle
    } from 'vuetify/lib'
    export default {
        components: {
            AppLayout,
            // VUETIFY COMPONENTS
            VApp,
            VBtn,
            VCalendar,
            VCard,
            VCardActions,
            VCardText,
            VCol,
            VIcon,
            VMenu,
            VList,
            VListItem,
            VListItemTitle,
            VRow,
            VSpacer,
            VSheet,
            VToolbar,
            VToolbarTitle, 
        },
        data(){
            return {
				locale: 'es',
                focus : '',
                type  : 'month',
                typeToLabel: {
                    month  : 'Mes',
                    week   : 'Semana',
                    day    : 'Día',
                    '4day' : 'Mes',
                },
                events      : [],
                selectedOpen: false,
                events      : [],
                errors      : [],
   				errs        : '',
                event:{
                    name        : '',
                    description : '',
                    startDate   : '',
                    endDate     : '',
                    startHour   : '',
                    endHour     : '',
                    color       : '#5d6ad0',
                },
                showUpdateEvent: false,
                showDeleteEvent: false
            }
        },
        mounted () {
            this.getEvents()
            $(".v-event").css({'color':'#fff!important'})
        },
        methods:{
            // CALENDAR DEFAULT EVENTS
            viewDay ({ date }) {
                console.log(date)
                this.focus = date
                this.type  = 'day'
            },
            getEventColor (event) {
                return event.color
            },
            setToday () {
                this.focus = ''
            },
            // 
            prev () {
                this.$refs.calendar.prev()
            },
            next () {
                this.$refs.calendar.next()
            },
            showEvent ({ nativeEvent, event }) {
                const open = () => {
                    this.event   = event
                    $("#modalEvent").modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                    setTimeout(() => this.selectedOpen = true, 10)
                }
                if (this.selectedOpen)
                {
                    this.selectedOpen = false
                    setTimeout(open, 10)
                }
                else
                    open()
            },
            getEventTitle(e){
                return e.input.title
            },
            rnd (a, b) {
                return Math.floor((b - a + 1) * Math.random()) + a
            },
            updateRange ({ start, end }) {
                this.start = start
                this.end   = end
            },
            // END CALENDAR DEFAULT EVENTS
            // CRUD
            resetEvent(){
                this.event = {
                    name        : '',
                    description : '',
                    startDate   : '',
                    endDate     : '',
                    startHour   : '',
                    endHour     : '',
                    color       : '#5d6ad0',
                };
            },
            validateEvent(){
                if(
                    this.event.name.trim()        &&
                    this.event.description.trim() &&
                    this.event.startDate.trim()   &&
                    this.event.endDate.trim()     &&
                    this.event.startHour.trim()   &&
                    this.event.endHour.trim()     &&
                    this.event.color.trim()       
                )
                {
                	this.event.start = `${this.event.startDate} ${this.event.startHour}`;
                	this.event.end   = `${this.event.endDate} ${this.event.endHour}`;
                    if( this.event.start >= this.event.end)
                    {
                        alert('La fecha y hora de inicio, no pueden ser iguales a la fecha y hora final');
                        return false;
                	}
                	return true;
                }
                else 
                {
                    alert('Completa todos los campos');
                    return false;
                }
            },
            showModalAddEvent(){
                this.resetEvent();
                $("#modalAddEvent").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            hideModalAddEvent(){
                this.resetEvent();
                $("#modalAddEvent").modal('hide')
            },
            hideModalEvent(){
                this.resetEvent()
                $("#modalEvent").modal('hide')
                this.selectedOpen = false
            },
            mtdShowUpdateEvent(event){
                this.event           = event;
                this.event.startDate = event.start.substring(0, 10)
                this.event.endDate   = event.end.substring(0, 10)
                this.event.startHour = event.start.substring(11, 16)
                this.event.endHour   = event.end.substring(11, 16)
                this.showUpdateEvent = true;
            },
            hideModalEvent(){
                this.resetEvent()
                $("#modalEvent").modal('hide')
                this.selectedOpen = false
            },
            // CRUD
            getEvents(){
                axios.post('calendars/index', {})
                .then(response => {
                    this.events = response.data.data
                })
                .catch(error => {
                    if(error.response != null)
                    {
                        if(error.response.status == 404)
                            return window.location = '/';
                    }
                });
            },
            addEvent(){
                if(this.validateEvent())
                {
                    axios.post('calendar', this.event)
                    .then(response => {
                        this.hideModalAddEvent()
                        this.events.push(response.data)
                        alert('Evento agregado con exito');
                    })
                    .catch(error => {
                        if(error.response != null)
                        {
                            if(error.response.status == 422)
                                this.errors = error.response.data.errors;
                        }
                    });
                }
            },
            updateEvent(event){
                if(this.validateEvent())
                {
                    axios.put(`calendar/${event.id}`, this.event)
                    .then(response => {
                        alert('Evento actualizado con exito');
                        this.showUpdateEvent = false;
                        this.events.splice(this.events.findIndex(item => item.id === event.id), 1, response.data)
                    })
                    .catch(error => {
                        if(error.response != null)
                        {
                            if(error.response.status == 422)
                                this.errors = error.response.data.errors;
                        }
                    });
                } 
            },
            deleteEvent(event){
                axios.delete(`calendar/${event.id}`, this.event)
                .then(response => {
                    alert('Evento eliminado con exito');
                    this.events.splice(this.events.findIndex(item => item.id === event.id), 1)
                    this.hideModalEvent()
                    this.resetEvent()
                })
                .catch(error => {
                    if(error.response != null)
                    {
                        if(error.response.status == 422)
                            this.errors = error.response.data.errors;
                    }
                });
            }
        }
    }
</script>

