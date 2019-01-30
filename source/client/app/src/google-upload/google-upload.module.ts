import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

import { GoogleUploadComponent } from './google-upload.component';
import { GoogleUploadService } from './google-upload.service';

@NgModule({
    declarations: [
        GoogleUploadComponent
    ],
    imports: [
        CommonModule,
        HttpClientModule
    ],
    exports: [
        GoogleUploadComponent
    ],
    providers: [
        GoogleUploadService
    ]
})

export class GoogleUploadModule { }