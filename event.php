<!-- Fenêtre modale -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer un Événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="mb-3">
                        <label for="eventName" class="form-label">Nom de l'événement</label>
                        <input type="text" class="form-control" id="eventName" required>
		    </div>
                   <!--div class="mb-3">
                       <label for="eventColor" class="form-label">Couleur</label>
                       <select class="form-select color-dropdown" id="eventColor">
                           <option value="red" data-color="red">Rouge</option>
                           <option value="blue" data-color="blue">Bleu</option>
                           <option value="green" data-color="green">Vert</option>
                           <option value="yellow" data-color="yellow">Jaune</option>
                       </select>
		   </div-->
                   <!--div class="mb-3">
                       <label class="form-label">Couleur</label>
                       <div id="colorPicker" class="color-picker">
                           <div class="color-circle" data-color="red" style="background-color: red;"></div>
                           <div class="color-circle" data-color="blue" style="background-color: blue;"></div>
                           <div class="color-circle" data-color="green" style="background-color: green;"></div>
                           <div class="color-circle" data-color="yellow" style="background-color: yellow;"></div>
                       </div>
                       <input type="hidden" id="selectedColor" value="red"> <Stocke la couleur choisie >
		   </div-->
                   <div class="mb-3">
                       <label for="eventColor" class="form-label">Couleur</label>
                       <div class="color-select-container">
                           <div id="indicator" class="color-indicator"></div> <!-- Cercle affiché -->
                           <select class="form-select color-dropdown" id="eventColor">
                               <option value="red" data-color="red">Rouge</option>
                               <option value="blue" data-color="blue">Bleu</option>
                               <option value="green" data-color="green">Vert</option>
                               <option value="yellow" data-color="yellow">Jaune</option>
                           </select>
                       </div>
		   </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Heure de début</label>
                        <input type="time" class="form-control" id="startTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">Heure de fin</label>
                        <input type="time" class="form-control" id="endTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDetails" class="form-label">Détails</label>
                        <textarea class="form-control" id="eventDetails" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="myEvent.createEvent()">Créer</button>
            </div>
        </div>
    </div>
</div>
