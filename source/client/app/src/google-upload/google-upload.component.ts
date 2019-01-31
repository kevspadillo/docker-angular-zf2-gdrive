import { Component, OnInit, ViewChild } from '@angular/core';

import { GoogleUploadService } from './google-upload.service';

@Component({
  selector: 'app-upload',
  templateUrl: './google-upload.component.html',
  styleUrls: ['./google-upload.component.css']
})

export class GoogleUploadComponent {

    @ViewChild('file') file;
    @ViewChild('username') username;

    // Flag to display filename in input form
    isFileChosen:boolean = false;
    
    // Filename to display
    fileName: string = '';

    constructor(public GoogleUploadService: GoogleUploadService) {}

    // On change of file, set file for upload and filename for display
    onFilesAdded() {
        const files: { [key: string]: File } = this.file.nativeElement.files;
        this.file = files[0];

        if (this.file.length > 0){
          this.isFileChosen = true;
        }        
        this.fileName = this.file.name;
    }

    uploadFile() {
        this.GoogleUploadService.uploadFile(this.username.nativeElement.value, this.file)
            .subscribe(event => {
                console.log(event);
            }
        );
    }
}