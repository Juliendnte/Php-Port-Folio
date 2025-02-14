<select required name="skill" id="skill">
    <?php foreach ($availableSkills as $skill): ?>
        <option value="<?= htmlspecialchars($skill['id']) ?>">
            <?= htmlspecialchars($skill['name']) ?>
        </option>
    <?php endforeach; ?>
</select>