@extends('layouts.app')

@section('title', 'Digital ID Card - Membership App')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Your Digital ID Card</h1>
            <p class="text-gray-600 mt-2">Professional membership identification</p>
        </div>

        <!-- ID Card Container -->
        <x-id :user="$user"></x-id>
    </div>
</div>

<!-- Include html2canvas for downloading the ID card -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadIDCard() {
    const cardElement = document.getElementById('idCard');
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;

    html2canvas(cardElement, {
        scale: 3, // Higher quality
        useCORS: true,
        logging: false,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        // Convert canvas to image
        const image = canvas.toDataURL('image/png', 1.0);
        
        // Create download link
        const link = document.createElement('a');
        link.download = 'membership-id-{{ $user->member_id }}.png';
        link.href = image;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Show success message
        showNotification('ID card downloaded successfully!', 'success');
    }).catch(error => {
        console.error('Error generating ID card:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Error downloading ID card. Please try again.', 'error');
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Print styles
const printStyles = `
    @media print {
        nav, .print-hide, button, [class*="bg-gradient"]:not(#idCard *) {
            display: none !important;
        }
        body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        #idCard {
            margin: 0 auto !important;
            box-shadow: none !important;
            border: 2px solid #000 !important;
            transform: none !important;
        }
        .min-h-screen {
            min-height: auto !important;
            background: white !important;
        }
        .max-w-6xl {
            max-width: none !important;
        }
    }
`;

// Add print styles to document
const styleSheet = document.createElement('style');
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
</script>

@endsection