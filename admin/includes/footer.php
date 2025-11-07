        </div> <!-- Fecha row -->
    </div> <!-- Fecha container-fluid -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Atualizar horário em tempo real
        function atualizarHorario() {
            const agora = new Date();
            const options = { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric',
                hour: '2-digit', 
                minute: '2-digit'
            };
            document.querySelector('.navbar-text').innerHTML = 
                `<i class="bi bi-calendar-check me-2"></i>${agora.toLocaleDateString('pt-BR', options)}`;
        }
        
        setInterval(atualizarHorario, 60000);
        
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>