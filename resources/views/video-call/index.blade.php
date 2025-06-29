<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Call</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1a1a1a;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }
        
        .video-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .video-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #2a2a2a;
        }
        
        .video-local {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #fff;
            background: #333;
            z-index: 10;
        }
        
        .controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 20;
        }
        
        .control-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
        }
        
        .control-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-mic {
            background: #4CAF50;
        }
        
        .btn-mic.muted {
            background: #f44336;
        }
        
        .btn-video {
            background: #2196F3;
        }
        
        .btn-video.disabled {
            background: #f44336;
        }
        
        .btn-end {
            background: #f44336;
        }
        
        .status {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            background: rgba(0,0,0,0.7);
            padding: 10px 15px;
            border-radius: 20px;
            z-index: 10;
        }
        
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 5;
        }
        
        .spinner {
            border: 4px solid #333;
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="video-container">
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Connecting...</p>
        </div>
        
        <div class="status" id="status">
            Initializing call...
        </div>
        
        <video id="remoteVideo" class="video-main" autoplay playsinline></video>
        <video id="localVideo" class="video-local" autoplay muted playsinline></video>
        
        <div class="controls">
            <button class="control-btn btn-mic" id="micBtn" onclick="toggleMic()">
                <svg id="micIcon" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2a3 3 0 0 1 3 3v6a3 3 0 0 1-6 0V5a3 3 0 0 1 3-3z"/>
                    <path d="M19 10v1a7 7 0 0 1-14 0v-1"/>
                    <path d="M12 18v4"/>
                    <path d="M8 22h8"/>
                </svg>
            </button>
            
            <button class="control-btn btn-video" id="videoBtn" onclick="toggleVideo()">
                <svg id="videoIcon" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23 7l-7 5 7 5V7z"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
            </button>
            
            <button class="control-btn btn-end" onclick="endCall()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        class VideoCall {
            constructor(callId) {
                this.callId = callId;
                this.localStream = null;
                this.remoteStream = null;
                this.peerConnection = null;
                this.isInitiator = false;
                this.isMicMuted = false;
                this.isVideoDisabled = false;
                this.pusher = null;
                this.channel = null;
                
                this.init();
            }
            
            async init() {
                try {
                    await this.setupPusher();
                    await this.getUserMedia();
                    await this.setupPeerConnection();
                    this.updateStatus('Connected - Waiting for peer...');
                } catch (error) {
                    console.error('Initialization failed:', error);
                    this.updateStatus('Failed to initialize call');
                }
            }
            
            setupPusher() {
                // Initialize Pusher
                this.pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                    encrypted: true,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }
                });
                
                this.channel = this.pusher.subscribe(`private-user.{{ auth()->id() }}`);
                
                // Listen for WebRTC signaling
                this.channel.bind('video.call.offer', (data) => {
                    if (data.call_id === this.callId) {
                        this.handleOffer(data.offer);
                    }
                });
                
                this.channel.bind('video.call.answer', (data) => {
                    if (data.call_id === this.callId) {
                        this.handleAnswer(data.answer);
                    }
                });
                
                this.channel.bind('video.call.candidate', (data) => {
                    if (data.call_id === this.callId) {
                        this.handleCandidate(data.candidate);
                    }
                });
                
                this.channel.bind('video.call.end', (data) => {
                    if (data.call_id === this.callId) {
                        this.handleCallEnd();
                    }
                });
            }
            
            async getUserMedia() {
                try {
                    this.localStream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: true
                    });
                    
                    document.getElementById('localVideo').srcObject = this.localStream;
                    document.getElementById('loading').style.display = 'none';
                } catch (error) {
                    console.error('Error accessing media devices:', error);
                    throw new Error('Could not access camera/microphone');
                }
            }
            
            async setupPeerConnection() {
                const configuration = {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' }
                    ]
                };
                
                this.peerConnection = new RTCPeerConnection(configuration);
                
                // Add local stream tracks
                this.localStream.getTracks().forEach(track => {
                    this.peerConnection.addTrack(track, this.localStream);
                });
                
                // Handle remote stream
                this.peerConnection.ontrack = (event) => {
                    const [remoteStream] = event.streams;
                    document.getElementById('remoteVideo').srcObject = remoteStream;
                    this.updateStatus('Connected');
                };
                
                // Handle ICE candidates
                this.peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        this.sendCandidate(event.candidate);
                    }
                };
                
                // Connection state changes
                this.peerConnection.onconnectionstatechange = () => {
                    console.log('Connection state:', this.peerConnection.connectionState);
                    this.updateStatus(`Connection: ${this.peerConnection.connectionState}`);
                };
            }
            
            async createOffer() {
                try {
                    this.isInitiator = true;
                    const offer = await this.peerConnection.createOffer();
                    await this.peerConnection.setLocalDescription(offer);
                    this.sendOffer(offer);
                } catch (error) {
                    console.error('Error creating offer:', error);
                }
            }
            
            async handleOffer(offer) {
                try {
                    await this.peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
                    const answer = await this.peerConnection.createAnswer();
                    await this.peerConnection.setLocalDescription(answer);
                    this.sendAnswer(answer);
                } catch (error) {
                    console.error('Error handling offer:', error);
                }
            }
            
            async handleAnswer(answer) {
                try {
                    await this.peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
                } catch (error) {
                    console.error('Error handling answer:', error);
                }
            }
            
            async handleCandidate(candidate) {
                try {
                    await this.peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                } catch (error) {
                    console.error('Error handling candidate:', error);
                }
            }
            
            sendOffer(offer) {
                fetch('/video-call/offer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        call_id: this.callId,
                        offer: offer,
                        target_user_id: this.getTargetUserId()
                    })
                });
            }
            
            sendAnswer(answer) {
                fetch('/video-call/answer-webrtc', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        call_id: this.callId,
                        answer: answer,
                        target_user_id: this.getTargetUserId()
                    })
                });
            }
            
            sendCandidate(candidate) {
                fetch('/video-call/candidate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        call_id: this.callId,
                        candidate: candidate,
                        target_user_id: this.getTargetUserId()
                    })
                });
            }
            
            getTargetUserId() {
                // This should be passed from the backend or stored when call is initiated
                return window.targetUserId || null;
            }
            
            toggleMic() {
                if (this.localStream) {
                    const audioTrack = this.localStream.getAudioTracks()[0];
                    if (audioTrack) {
                        audioTrack.enabled = !audioTrack.enabled;
                        this.isMicMuted = !audioTrack.enabled;
                        
                        const micBtn = document.getElementById('micBtn');
                        const micIcon = document.getElementById('micIcon');
                        
                        if (this.isMicMuted) {
                            micBtn.classList.add('muted');
                            micIcon.innerHTML = `
                                <path d="M12 2a3 3 0 0 1 3 3v6a3 3 0 0 1-6 0V5a3 3 0 0 1 3-3z"/>
                                <path d="M19 10v1a7 7 0 0 1-14 0v-1"/>
                                <path d="M12 18v4"/>
                                <path d="M8 22h8"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            `;
                        } else {
                            micBtn.classList.remove('muted');
                            micIcon.innerHTML = `
                                <path d="M12 2a3 3 0 0 1 3 3v6a3 3 0 0 1-6 0V5a3 3 0 0 1 3-3z"/>
                                <path d="M19 10v1a7 7 0 0 1-14 0v-1"/>
                                <path d="M12 18v4"/>
                                <path d="M8 22h8"/>
                            `;
                        }
                    }
                }
            }
            
            toggleVideo() {
                if (this.localStream) {
                    const videoTrack = this.localStream.getVideoTracks()[0];
                    if (videoTrack) {
                        videoTrack.enabled = !videoTrack.enabled;
                        this.isVideoDisabled = !videoTrack.enabled;
                        
                        const videoBtn = document.getElementById('videoBtn');
                        const videoIcon = document.getElementById('videoIcon');
                        
                        if (this.isVideoDisabled) {
                            videoBtn.classList.add('disabled');
                            videoIcon.innerHTML = `
                                <path d="M23 7l-7 5 7 5V7z"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            `;
                        } else {
                            videoBtn.classList.remove('disabled');
                            videoIcon.innerHTML = `
                                <path d="M23 7l-7 5 7 5V7z"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            `;
                        }
                    }
                }
            }
            
            endCall() {
                // Send end call signal
                fetch('/video-call/end', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        call_id: this.callId,
                        target_user_id: this.getTargetUserId()
                    })
                });
                
                this.cleanup();
                window.location.href = '/friends';
            }
            
            handleCallEnd() {
                this.cleanup();
                alert('Call ended by peer');
                window.location.href = '/friends';
            }
            
            cleanup() {
                if (this.localStream) {
                    this.localStream.getTracks().forEach(track => track.stop());
                }
                
                if (this.peerConnection) {
                    this.peerConnection.close();
                }
                
                if (this.pusher) {
                    this.pusher.disconnect();
                }
            }
            
            updateStatus(message) {
                document.getElementById('status').textContent = message;
            }
        }
        
        // Initialize video call
        const callId = '{{ $callId }}';
        const videoCall = new VideoCall(callId);
        
        // Global functions for UI controls
        function toggleMic() {
            videoCall.toggleMic();
        }
        
        function toggleVideo() {
            videoCall.toggleVideo();
        }
        
        function endCall() {
            videoCall.endCall();
        }
        
        // Auto-initiate offer after a short delay
        setTimeout(() => {
            videoCall.createOffer();
        }, 2000);
    </script>
</body>
</html>