import { Component, OnInit, ViewChild } from '@angular/core';

import { GoogleUploadService } from './google-upload.service';

@Component({
  selector: 'app-upload',
  templateUrl: './google-upload.component.html',
  styleUrls: ['./google-upload.component.css']
})

export class GoogleUploadComponent {

    @ViewChild('file') file;

    constructor(public GoogleUploadService: GoogleUploadService) {}

    onFilesAdded() {
        const files: { [key: string]: File } = this.file.nativeElement.files;
        this.file = files[0];
    }

    uploadFile() {
        this.GoogleUploadService.uploadFile(this.file);
    }
}