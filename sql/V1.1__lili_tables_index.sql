ALTER TABLE `ll_song` 
ADD UNIQUE INDEX `idx_song_unique_title_artist` (`title` ASC, `artist` ASC);

ALTER TABLE `ll_song` 
ADD UNIQUE INDEX `idx_song_unique_zing_id` (`zing_id` ASC);