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

    // Errors
    errors:any = [];

    // Success message
    success:string = null;

    uploading:boolean = false;
    
    // Filename to display
    fileName:string = '';

    // Uploaded file
    uploadedFile:File;

    constructor(public GoogleUploadService: GoogleUploadService) {}

    // On change of file, set file for upload and filename for display
    onFilesAdded() {

        if (this.file.nativeElement.files.length > 0){
            const files: { [key: string]: File } = this.file.nativeElement.files;
            this.uploadedFile = files[0];
            this.isFileChosen = true;
        }        
        this.fileName = this.uploadedFile.name;
    }

    uploadFile() {

        if (this.username.nativeElement.value == '' || this.uploadedFile == undefined) {
            this.errors.push("All fields are required for upload.");
            return false;
        }

        this.uploading = true;
        this.errors    = []
        this.GoogleUploadService.uploadFile(this.username.nativeElement.value, this.uploadedFile)
            .subscribe(
                event => {
                    if (event.type == 4) {
                        this.success = "File Successfully Uploaded";
                        this.uploading                    = false;
                        this.file.nativeElement.file      = null;
                        this.username.nativeElement.value = null;
                        this.errors                       = [];
                        this.fileName                     = null;
                    }
                },
                error => {

                    this.uploading = false;
                    for (let key in error.error.message) {
                        for(let msg in error.error.message[key]) {
                            this.errors.push(error.error.message[key][msg]);
                        }
                    }
                }
        );
    }
}